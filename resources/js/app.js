import './bootstrap';

import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide';

const storageKey = 'fms-hci-preferences';
const defaultPreferences = {
    textSize: 'base',
    darkMode: false,
    highContrast: false,
    voiceAssistance: false,
};

const renderLucideIcons = () => {
    createIcons({ icons });
};

const readStoredPreferences = () => {
    try {
        const parsed = JSON.parse(localStorage.getItem(storageKey) || '{}');

        return { ...defaultPreferences, ...parsed };
    } catch (error) {
        return { ...defaultPreferences };
    }
};

const writeStoredPreferences = (preferences) => {
    localStorage.setItem(storageKey, JSON.stringify(preferences));
};

const applyPreferences = (preferences) => {
    const root = document.documentElement;

    root.classList.toggle('dark', !!preferences.darkMode);
    root.classList.toggle('hci-high-contrast', !!preferences.highContrast);
    root.dataset.textSize = preferences.textSize || 'base';
    root.dataset.voiceAssistance = preferences.voiceAssistance ? 'on' : 'off';
};

const localeDraftStorageKey = () => {
    const url = new URL(window.location.href);
    url.searchParams.delete('lang');

    return `fms-locale-switch:${url.pathname}?${url.searchParams.toString()}`;
};

const captureLocaleSwitchDraft = () => {
    const forms = Array.from(document.querySelectorAll('form'));

    if (forms.length === 0) {
        sessionStorage.removeItem(localeDraftStorageKey());
        return;
    }

    const payload = forms
        .map((form, index) => {
            const fields = [];

            Array.from(form.elements).forEach((field) => {
                if (!field.name || field.disabled || ['file', 'password', 'submit', 'button', 'image', 'reset'].includes(field.type)) {
                    return;
                }

                if ((field.type === 'checkbox' || field.type === 'radio') && !field.checked) {
                    return;
                }

                fields.push({
                    name: field.name,
                    type: field.type,
                    value: field.value,
                });
            });

            return {
                index,
                fields,
            };
        })
        .filter((form) => form.fields.length > 0);

    if (payload.length === 0) {
        sessionStorage.removeItem(localeDraftStorageKey());
        return;
    }

    sessionStorage.setItem(localeDraftStorageKey(), JSON.stringify(payload));
};

const restoreLocaleSwitchDraft = () => {
    const raw = sessionStorage.getItem(localeDraftStorageKey());

    if (!raw) {
        return;
    }

    try {
        const payload = JSON.parse(raw);
        const forms = Array.from(document.querySelectorAll('form'));

        payload.forEach((savedForm) => {
            const form = forms[savedForm.index];

            if (!form) {
                return;
            }

            savedForm.fields.forEach((savedField) => {
                const elements = form.querySelectorAll(`[name="${CSS.escape(savedField.name)}"]`);

                elements.forEach((field) => {
                    if (field.type === 'radio' || field.type === 'checkbox') {
                        field.checked = field.value === savedField.value;
                        field.dispatchEvent(new Event('change', { bubbles: true }));
                        return;
                    }

                    field.value = savedField.value;
                    field.dispatchEvent(new Event('input', { bubbles: true }));
                    field.dispatchEvent(new Event('change', { bubbles: true }));
                });
            });
        });
    } catch (error) {
        // Ignore malformed draft payloads.
    }

    sessionStorage.removeItem(localeDraftStorageKey());
};

const announce = (message) => {
    const region = document.getElementById('global-live-region');

    if (!region) {
        return;
    }

    region.textContent = '';

    window.setTimeout(() => {
        region.textContent = message;
    }, 20);
};

const stopSpeech = () => {
    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel();
    }
};

const speakText = (text) => {
    if (!('speechSynthesis' in window) || !text) {
        announce('Voice guidance is not supported on this browser.');
        return false;
    }

    stopSpeech();

    const utterance = new SpeechSynthesisUtterance(text);
    utterance.rate = 0.92;
    utterance.pitch = 1;
    utterance.lang = document.documentElement.lang || 'en';
    window.speechSynthesis.speak(utterance);
    announce('Voice guidance started.');

    return true;
};

const createVoiceRecognizer = (callbacks = {}) => {
    const Recognition = window.SpeechRecognition || window.webkitSpeechRecognition;

    if (!Recognition) {
        announce('Voice typing is not supported on this browser.');
        return null;
    }

    const recognition = new Recognition();
    recognition.lang = document.documentElement.lang || 'en';
    recognition.interimResults = true;
    recognition.maxAlternatives = 1;

    recognition.onstart = () => callbacks.onStart?.();
    recognition.onerror = (event) => callbacks.onError?.(event);
    recognition.onend = () => callbacks.onEnd?.();
    recognition.onresult = (event) => {
        const transcript = Array.from(event.results)
            .map((result) => result[0]?.transcript || '')
            .join(' ')
            .trim();

        callbacks.onResult?.(transcript, event.results[event.results.length - 1]?.isFinal ?? false);
    };

    return recognition;
};

window.renderLucideIcons = renderLucideIcons;
window.FMSHci = {
    announce,
    speakText,
    stopSpeech,
    createVoiceRecognizer,
    readStoredPreferences,
    captureLocaleSwitchDraft,
};

window.languageSwitcher = function languageSwitcher(config = {}) {
    return {
        locale: config.locale || 'en',
        init() {
            const remembered = localStorage.getItem('fms-language');

            if (remembered && remembered !== this.locale) {
                const url = new URL(window.location.href);
                url.searchParams.set('lang', remembered);
                window.location.replace(url.toString());
                return;
            }

            localStorage.setItem('fms-language', this.locale);
        },
        switchLocale(nextLocale) {
            if (!nextLocale || nextLocale === this.locale) {
                return;
            }

            captureLocaleSwitchDraft();
            localStorage.setItem('fms-language', nextLocale);

            const url = new URL(window.location.href);
            url.searchParams.set('lang', nextLocale);
            window.location.assign(url.toString());
        },
    };
};

window.hciPreferences = function hciPreferences(config = {}) {
    const stored = readStoredPreferences();
    const preferences = {
        ...stored,
        darkMode: stored.darkMode ?? !!config.dark_mode_enabled,
    };

    return {
        open: false,
        locale: config.locale || config.default_language || 'en',
        preferences,
        firstVisit: false,
        init() {
            this.firstVisit = localStorage.getItem('fms-hci-tour-dismissed') !== 'true';
            applyPreferences(this.preferences);
        },
        dismissTour() {
            this.firstVisit = false;
            localStorage.setItem('fms-hci-tour-dismissed', 'true');
        },
        persist() {
            writeStoredPreferences(this.preferences);
            applyPreferences(this.preferences);
        },
        changeLocale(value) {
            if (!value || value === this.locale) {
                return;
            }

            captureLocaleSwitchDraft();
            localStorage.setItem('fms-language', value);
            this.locale = value;

            const url = new URL(window.location.href);
            url.searchParams.set('lang', value);
            window.location.assign(url.toString());
        },
        setTextSize(value) {
            this.preferences.textSize = value;
            this.persist();
            announce(`Text size changed to ${value}.`);
        },
        toggleDarkMode() {
            this.preferences.darkMode = !this.preferences.darkMode;
            this.persist();
            announce(this.preferences.darkMode ? 'Dark mode enabled.' : 'Dark mode disabled.');
        },
        toggleHighContrast() {
            this.preferences.highContrast = !this.preferences.highContrast;
            this.persist();
            announce(this.preferences.highContrast ? 'High contrast mode enabled.' : 'High contrast mode disabled.');
        },
        toggleVoiceAssistance() {
            this.preferences.voiceAssistance = !this.preferences.voiceAssistance;
            this.persist();
            announce(this.preferences.voiceAssistance ? 'Voice assistance ready.' : 'Voice assistance disabled.');
        },
        readPage() {
            const main = document.getElementById('main-content');
            const text = main?.innerText?.replace(/\s+/g, ' ').trim().slice(0, 2400);

            speakText(text);
        },
        stopReading() {
            stopSpeech();
            announce('Voice guidance stopped.');
        },
    };
};

window.notificationCenter = function notificationCenter(config = {}) {
    return {
        notificationsOpen: false,
        items: config.initialItems || [],
        unreadCount: config.initialUnreadCount || 0,
        lastCheckedAt: null,
        pollHandle: null,
        browserAlertsEnabled: false,
        enabled: config.enabled !== false,
        init() {
            this.lastCheckedAt = new Date().toISOString();

            if (!this.enabled || !config.feedUrl) {
                return;
            }

            this.poll();
            this.pollHandle = window.setInterval(() => this.poll(), 30000);
        },
        destroy() {
            if (this.pollHandle) {
                window.clearInterval(this.pollHandle);
            }
        },
        badgeLabel() {
            return this.unreadCount > 99 ? '99+' : `${this.unreadCount}`;
        },
        async poll() {
            try {
                const response = await fetch(config.feedUrl, {
                    headers: {
                        Accept: 'application/json',
                    },
                });

                if (!response.ok) {
                    return;
                }

                const payload = await response.json();
                const previousUnread = this.unreadCount;

                this.enabled = payload.enabled !== false;
                this.items = payload.items || [];
                this.unreadCount = payload.unread_count || 0;
                this.lastCheckedAt = new Date().toISOString();

                if (previousUnread < this.unreadCount && this.items.length > 0) {
                    const newest = this.items.find((item) => item.is_unread) || this.items[0];
                    announce(`New notification: ${newest.message}`);

                    if (this.browserAlertsEnabled && 'Notification' in window && Notification.permission === 'granted') {
                        new Notification('Feedback Management System', {
                            body: newest.message,
                        });
                    }
                }
            } catch (error) {
                // Ignore polling errors so the UI stays usable offline.
            }
        },
        async markAllRead() {
            await fetch(config.markAllUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                    'X-CSRF-TOKEN': config.csrfToken,
                    Accept: 'application/json',
                },
                body: new URLSearchParams({ _token: config.csrfToken }).toString(),
            });

            this.items = this.items.map((item) => ({ ...item, is_unread: false }));
            this.unreadCount = 0;
            announce('All notifications marked as read.');
        },
        async openNotification(item) {
            if (item.is_unread) {
                await fetch(config.readUrlTemplate.replace('__ID__', item.id), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                        'X-CSRF-TOKEN': config.csrfToken,
                        Accept: 'application/json',
                    },
                    body: new URLSearchParams({
                        _token: config.csrfToken,
                        redirect_to: item.url,
                    }).toString(),
                });

                item.is_unread = false;
                this.unreadCount = Math.max(0, this.unreadCount - 1);
            }

            window.location.href = item.url;
        },
        async enableBrowserAlerts() {
            if (!('Notification' in window)) {
                announce('Browser alerts are not supported on this device.');
                return;
            }

            const permission = await Notification.requestPermission();
            this.browserAlertsEnabled = permission === 'granted';
            announce(this.browserAlertsEnabled ? 'Browser alerts enabled.' : 'Browser alerts were not enabled.');
        },
    };
};

window.Alpine = Alpine;

Alpine.start();

// Open the current page with show_my_requests=1 to trigger auto-tracking from the sidebar
window.openMyRequests = function () {
    try {
        const url = new URL(window.location.href);
        url.searchParams.set('show_my_requests', '1');
        window.location.href = url.toString();
    } catch (e) {
        // fallback
        window.location.href = window.location.pathname + '?show_my_requests=1';
    }
};

document.addEventListener('DOMContentLoaded', renderLucideIcons);
document.addEventListener('alpine:navigated', renderLucideIcons);
document.addEventListener('DOMContentLoaded', restoreLocaleSwitchDraft);
document.addEventListener('alpine:navigated', restoreLocaleSwitchDraft);
