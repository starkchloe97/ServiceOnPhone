import { json } from '../_shared/respond.js'

const MAX_BODY_BYTES = 16 * 1024
const ALLOWED_SOURCES = new Set(['contact', 'callback', 'service'])

function cleanText(value, maxLength) {
  if (typeof value !== 'string') return ''

  return value
    .replace(/\0/g, '')
    .replace(/<[^>]*>/g, '')
    .trim()
    .slice(0, maxLength)
}

function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email) && email.length <= 190
}

export async function onRequestPost({ request, env }) {
  const contentType = request.headers.get('content-type') || ''
  if (!contentType.toLowerCase().includes('application/json')) {
    return json({ success: false, message: 'Send the form using a JSON request.' }, 415)
  }

  const contentLength = Number(request.headers.get('content-length') || 0)
  if (contentLength > MAX_BODY_BYTES) {
    return json({ success: false, message: 'The submitted form is too large.' }, 413)
  }

  let body
  try {
    body = await request.json()
  } catch {
    return json({ success: false, message: 'Invalid request. Please refresh the page and try again.' }, 400)
  }

  if (!body || typeof body !== 'object' || Array.isArray(body)) {
    return json({ success: false, message: 'Invalid form data.' }, 400)
  }

  // Silently discard automated submissions that fill the hidden honeypot field.
  if (cleanText(body.website, 200) !== '') {
    return json({ success: true, message: 'Thank you. Your request has been received.' })
  }

  const name = cleanText(body.name, 100)
  const phone = cleanText(body.phone, 24)
  const email = cleanText(body.email, 190)
  const message = cleanText(body.message, 2000)
  const requestedSource = cleanText(body.source, 24)
  const source = ALLOWED_SOURCES.has(requestedSource) ? requestedSource : 'contact'

  if (!name || !phone) {
    return json({ success: false, message: 'Please provide your name and phone number.' }, 422)
  }

  if (/[\r\n]/.test(name)) {
    return json({ success: false, message: 'Please enter a valid name.' }, 422)
  }

  if (!/^[0-9+\s().-]{7,24}$/.test(phone)) {
    return json({ success: false, message: 'Please enter a valid phone number.' }, 422)
  }

  if (email && !isValidEmail(email)) {
    return json({ success: false, message: 'Please enter a valid email address.' }, 422)
  }

  if (!env.DB) {
    console.error('ServiceOnPhone: D1 binding DB is missing.')
    return json({
      success: false,
      message: 'The contact form is temporarily unavailable. Please contact us by phone.',
    }, 503)
  }

  try {
    const id = crypto.randomUUID()
    const sql = 'INSERT INTO service_leads (id, name, phone, email, message, source) ' +
      'VALUES (?, ?, ?, ?, ?, ?)'

    await env.DB.prepare(sql)
      .bind(id, name, phone, email || null, message || null, source)
      .run()

    return json({
      success: true,
      message: 'Thank you. Your request has been received. Our team will contact you soon.',
    }, 201)
  } catch (error) {
    console.error('ServiceOnPhone lead insert failed:', error)
    return json({
      success: false,
      message: 'We could not submit your request right now. Please try again later or call us.',
    }, 500)
  }
}
