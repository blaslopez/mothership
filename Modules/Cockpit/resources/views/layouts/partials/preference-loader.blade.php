<script>
const Preferences = {
    storageKey: 'mothership_preferences',

    defaults: {
        global: { theme: 'light', layout: 'default', locale: 'en' }
    },

    load() {
        try {
            return JSON.parse(localStorage.getItem(this.storageKey)) || {};
        } catch {
            return {};
        }
    },

    save(prefs) {
        localStorage.setItem(this.storageKey, JSON.stringify(prefs));
    },

    get(module, key) {
        const prefs = this.load();
        return prefs[module]?.[key] ?? this.defaults[module]?.[key] ?? null;
    },

    set(module, key, value) {
        const prefs = this.load();
        if (!prefs[module]) prefs[module] = {};
        prefs[module][key] = value;
        this.save(prefs);
        this.apply(module, key, value);
    },

    apply(module, key, value) {
        if (module === 'global') {
            if (key === 'theme')  document.documentElement.setAttribute('data-theme', value);
            if (key === 'layout') document.body.className = `layout-${value}`;
        }
    },

    applyAll() {
        const prefs = this.load();
        for (const module in prefs) {
            for (const key in prefs[module]) {
                this.apply(module, key, prefs[module][key]);
            }
        }
    },

    @guest
    // Guest: apply stored preferences from localStorage
    init() {
        this.applyAll();
    },

    clear() {
        localStorage.removeItem(this.storageKey);
    }
    @else
    // Authenticated: preferences come from server, localStorage not used
    init() {},
    clear() { localStorage.removeItem('{{ $storageKey ?? "mothership_preferences" }}'); }
    @endguest
};

Preferences.init();
</script>
