function updateClocks() {
    const now = new Date();
    document.getElementById('utcClock').textContent = now.toUTCString().split(" ")[4];
    document.getElementById('localClock').textContent = now.toLocaleTimeString();
}
setInterval(updateClocks, 1000);
updateClocks();

const sessions = {
    Sydney: [22, 7],
    Tokyo: [0, 9],
    Frankfurt: [6, 15],
    London: [7, 16],
    NewYork: [12, 21],
};

const pairsBySession = {
    Sydney: "AUD/USD, NZD/USD",
    Tokyo: "USD/JPY, AUD/JPY, EUR/JPY",
    Frankfurt: "EUR/USD, EUR/CHF",
    London: "GBP/USD, EUR/USD, EUR/GBP",
    NewYork: "USD/CAD, GBP/USD, EUR/USD, XAU/USD"
};

function highlightSession() {
    const now = new Date();
    const utcHour = now.getUTCHours();
    let active = null;

    for (let session in sessions) {
        let [start, end] = sessions[session];
        let isActive = start <= end ? (utcHour >= start && utcHour < end) : (utcHour >= start || utcHour < end);
        const el = document.getElementById(session);
        const sessionClass = session.toLowerCase();
        if (isActive) {
            el.classList.add("active", sessionClass);
            active = session;
        } else {
            el.classList.remove("active", sessionClass);
        }
    }

    document.getElementById("pairs").textContent = active ? `Best Pairs: ${pairsBySession[active]}` : "Best Pairs: -";
}

function getNextSession(utcHour) {
    let sorted = Object.entries(sessions).map(([name, [start]]) => ({ name, start }));
    sorted.sort((a, b) => a.start - b.start);
    for (let s of sorted) if (utcHour < s.start) return s;
    return sorted[0];
}

function updateCountdown() {
    const now = new Date();
    const utcHour = now.getUTCHours();
    const next = getNextSession(utcHour);
    let nextStart = new Date(Date.UTC(now.getUTCFullYear(), now.getUTCMonth(), now.getUTCDate(), next.start, 0, 0));
    if (next.start <= utcHour) nextStart.setUTCDate(nextStart.getUTCDate() + 1);

    let diff = Math.floor((nextStart - now) / 1000);
    let hours = Math.floor(diff / 3600);
    let minutes = Math.floor((diff % 3600) / 60);
    let seconds = diff % 60;

    document.getElementById("countdown").textContent = `Next Session: ${next.name} opens in ${hours}h ${minutes}m ${seconds}s`;
}

setInterval(highlightSession, 60000);
highlightSession();

setInterval(updateCountdown, 1000);
updateCountdown();
