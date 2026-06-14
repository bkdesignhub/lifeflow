const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
const toastEl = document.getElementById('appToast');
const toast = toastEl ? new bootstrap.Toast(toastEl) : null;
let deferredInstallPrompt = null;

if (window.lucide) {
    lucide.createIcons({ attrs: { 'stroke-width': 2 } });
}

function showToast(message, type = 'success') {
    if (!toastEl) return;
    toastEl.classList.toggle('bg-danger', type === 'error');
    toastEl.classList.toggle('bg-success', type === 'success');
    toastEl.querySelector('.toast-body').textContent = message;
    toast.show();
}

function clearInlineErrors(form) {
    form.querySelectorAll('.invalid-feedback.dynamic').forEach((item) => item.remove());
    form.querySelectorAll('.is-invalid').forEach((item) => item.classList.remove('is-invalid'));
}

function showInlineErrors(form, errors = {}) {
    Object.entries(errors).forEach(([name, messages]) => {
        const field = form.querySelector(`[name="${name}"]`);
        if (!field) return;
        field.classList.add('is-invalid');
        const feedback = document.createElement('div');
        feedback.className = 'invalid-feedback dynamic';
        feedback.textContent = Array.isArray(messages) ? messages[0] : messages;
        field.insertAdjacentElement('afterend', feedback);
    });
}

async function sendRequest(url, method = 'POST', body = null) {
    const options = { method, headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' } };
    if (body instanceof FormData) {
        options.body = body;
    } else if (body) {
        options.headers['Content-Type'] = 'application/json';
        options.body = JSON.stringify(body);
    }
    const response = await fetch(url, options);
    const data = await response.json().catch(() => ({}));
    if (!response.ok) {
        const error = new Error(data.message || Object.values(data.errors || {})[0]?.[0] || 'Something went wrong.');
        error.errors = data.errors || {};
        throw error;
    }
    if (window.lucide) lucide.createIcons();
    return data;
}

async function sendFormRequest(form) {
    const response = await fetch(form.action, {
        method: form.querySelector('[name="_method"]') ? 'POST' : form.method.toUpperCase(),
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body: new FormData(form),
    });
    const data = await response.json().catch(() => ({}));
    if (!response.ok) {
        const message = data.message || Object.values(data.errors || {})[0]?.[0] || 'Something went wrong.';
        const error = new Error(message);
        error.errors = data.errors || {};
        throw error;
    }
    return data;
}

document.addEventListener('submit', async (event) => {
    const form = event.target.closest('.ajax-form');
    if (!form) return;
    event.preventDefault();
    clearInlineErrors(form);
    const button = form.querySelector('button[type="submit"], button:not([type])');
    const original = button?.innerHTML;
    if (button) { button.disabled = true; button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving'; }
    try {
        const data = await sendFormRequest(form);
        showToast(data.message || 'Saved.');
        if (data.redirect) window.location.href = data.redirect;
        else if (form.dataset.reload === 'true') window.location.reload();
    } catch (error) {
        showInlineErrors(form, error.errors);
        showToast(error.message, 'error');
    } finally {
        if (button) { button.disabled = false; button.innerHTML = original; }
    }
});

document.addEventListener('click', async (event) => {
    const actionEl = event.target.closest('[data-action]');
    if (!actionEl) return;
    event.preventDefault();
    if (actionEl.dataset.confirm && !confirm(actionEl.dataset.confirm)) return;
    try {
        const payload = actionEl.dataset.payload ? JSON.parse(actionEl.dataset.payload) : {};
        const data = await sendRequest(actionEl.dataset.action, actionEl.dataset.method || 'POST', payload);
        showToast(data.message || 'Done.');
        const row = actionEl.closest('[id^="task-"], [id^="reminder-"], [id^="note-"]');
        if ((actionEl.dataset.method || '').toUpperCase() === 'DELETE' && row) row.remove();
        if (actionEl.closest('.status-chip')) actionEl.textContent = 'Done';
        if (window.lucide) lucide.createIcons();
    } catch (error) {
        showToast(error.message, 'error');
    }
});

document.querySelectorAll('.icon-choice').forEach((button) => {
    button.addEventListener('click', () => {
        document.querySelectorAll('.icon-choice').forEach((item) => item.classList.remove('active'));
        button.classList.add('active');
        document.getElementById('taskIcon').value = button.dataset.icon;
    });
});

document.querySelectorAll('.category-choice').forEach((button) => {
    button.addEventListener('click', () => {
        document.querySelectorAll('.category-choice').forEach((item) => item.classList.remove('active'));
        button.classList.add('active');
        document.getElementById('moneyCategory').value = button.dataset.category;
        document.getElementById('moneyIcon').value = button.dataset.icon;
    });
});

const assistantForm = document.getElementById('assistantForm');
if (assistantForm) {
    document.querySelectorAll('[data-suggestion]').forEach((button) => {
        button.addEventListener('click', () => {
            assistantForm.message.value = button.dataset.suggestion;
            assistantForm.requestSubmit();
        });
    });
    assistantForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        const input = assistantForm.message;
        const message = input.value.trim();
        if (!message) return;
        appendMessage(message, 'user');
        input.value = '';
        try {
            const data = await sendRequest(assistantForm.action, 'POST', { message });
            appendMessage(data.message, 'bot');
        } catch (error) {
            appendMessage(error.message, 'bot');
        }
    });
}

function appendMessage(message, type) {
    const log = document.getElementById('chatLog');
    const bubble = document.createElement('div');
    bubble.className = `msg ${type}`;
    bubble.textContent = message;
    log.appendChild(bubble);
    log.scrollTop = log.scrollHeight;
}

window.addEventListener('beforeinstallprompt', (event) => {
    event.preventDefault();
    deferredInstallPrompt = event;
});

document.getElementById('installBtn')?.addEventListener('click', async () => {
    if (!deferredInstallPrompt) {
        showToast('Use your browser menu to install LifeFlow.');
        return;
    }
    deferredInstallPrompt.prompt();
    await deferredInstallPrompt.userChoice;
    deferredInstallPrompt = null;
});

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/service-worker.js');
}

async function setupFirebaseMessaging() {
    const configEl = document.querySelector('meta[name="firebase-config"]');
    if (!configEl || !window.firebase?.messaging) return;
    const config = JSON.parse(configEl.content || '{}');
    if (!config.api_key || !config.project_id || !config.vapid_key) return;
    const permission = await Notification.requestPermission();
    if (permission !== 'granted') return;
    firebase.initializeApp({
        apiKey: config.api_key,
        authDomain: config.auth_domain,
        projectId: config.project_id,
        messagingSenderId: config.messaging_sender_id,
        appId: config.app_id,
    });
    const token = await firebase.messaging().getToken({ vapidKey: config.vapid_key });
    if (token) await sendRequest(document.querySelector('meta[name="fcm-token-url"]').content, 'POST', { token });
}

setupFirebaseMessaging().catch(() => {});
