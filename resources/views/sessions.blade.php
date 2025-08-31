<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>Trading Sessions Dashboard</title>
    @vite('resources/css/app.css')
    <style>
        body {
            background: #0b0f19;
            color: #fff;
            font-family: 'Orbitron', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .dashboard {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 2rem;
            padding: 3rem 4rem;
            text-align: center;
            box-shadow: 0 0 35px rgba(0, 255, 255, 0.25);
        }

        /* Futuristic glowing header */
        .dashboard-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: cyan;
            text-transform: uppercase;
            letter-spacing: 3px;
            animation: glow-fade 2.5s infinite alternate;
        }

        @keyframes glow-fade {
            from {
                text-shadow: 0 0 10px cyan, 0 0 20px #00fff2;
            }

            to {
                text-shadow: 0 0 25px #00fff2, 0 0 50px cyan, 0 0 75px cyan;
            }
        }

        .sessions {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-gap: 3rem;
            margin-top: 2.5rem;
            justify-items: center;
        }

        .session {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
            cursor: pointer;
            color: #fff;
            position: relative;
            background: #111;
            border: 3px solid cyan;
            transition: all 0.3s ease-in-out;
        }

        .session span {
            position: relative;
            z-index: 2;
        }

        .session:hover {
            transform: scale(1.08);
            box-shadow: 0 0 20px cyan;
        }

        /* Active session variations */
        .session.active.sydney {
            animation: pulse-sydney 2s infinite alternate;
        }

        .session.active.tokyo {
            animation: pulse-tokyo 2s infinite alternate;
        }

        .session.active.frankfurt {
            animation: pulse-frankfurt 2s infinite alternate;
        }

        .session.active.london {
            animation: pulse-london 2s infinite alternate;
        }

        .session.active.newyork {
            animation: pulse-newyork 2s infinite alternate;
        }

        /* Pulse Animations */
        @keyframes pulse-sydney {
            from {
                box-shadow: 0 0 25px #ffb347, 0 0 50px #ffb347;
            }

            to {
                box-shadow: 0 0 45px #ffb347, 0 0 90px #ffb347;
            }
        }

        @keyframes pulse-tokyo {
            from {
                box-shadow: 0 0 25px #ff0066, 0 0 50px #ff0066;
            }

            to {
                box-shadow: 0 0 45px #ff0066, 0 0 90px #ff0066;
            }
        }

        @keyframes pulse-frankfurt {
            from {
                box-shadow: 0 0 25px #00ffcc, 0 0 50px #00ffcc;
            }

            to {
                box-shadow: 0 0 45px #00ffcc, 0 0 90px #00ffcc;
            }
        }

        @keyframes pulse-london {
            from {
                box-shadow: 0 0 25px #66ff00, 0 0 50px #48ff00;
            }

            to {
                box-shadow: 0 0 45px #5eff00, 0 0 90px #7bff00;
            }
        }

        @keyframes pulse-newyork {
            from {
                box-shadow: 0 0 25px #009dff, 0 0 50px #009dff;
            }

            to {
                box-shadow: 0 0 45px #009dff, 0 0 90px #009dff;
            }
        }

        .clock {
            margin-top: 1.5rem;
            font-size: 1.5rem;
            color: cyan;
        }

        .pairs {
            margin-top: 3rem;
            font-size: 1.1rem;
            color: #00f5ff;
        }

        .countdown {
            margin-top: 1rem;
            font-size: 1.2rem;
            color: #ffcc00;
            text-shadow: 0 0 10px #ffcc00, 0 0 20px #ffcc00;
        }
    </style>
</head>

<body>
    <div class="dashboard">
        <h2 class="dashboard-title">Trading Sessions</h2>
        <div class="clock">
            UTC: <span id="utcClock"></span><br>
            Local: <span id="localClock"></span>
        </div>

        <div class="sessions">
            <div id="Sydney" class="session">Sydney</div>
            <div id="Tokyo" class="session">Tokyo</div>
            <div id="Frankfurt" class="session">Frankfurt</div>
            <div id="London" class="session">London</div>
            <div id="NewYork" class="session">New York</div>
        </div>

        <div class="pairs" id="pairs">Best Pairs: -</div>
        <div class="countdown" id="countdown">Next Session: -</div>
    </div>

    <script>
        function updateClocks() {
            const now = new Date();
            document.getElementById('utcClock').textContent = now.toUTCString().split(" ")[4];
            document.getElementById('localClock').textContent = now.toLocaleTimeString();
        }
        setInterval(updateClocks, 1000);
        updateClocks();

        // sessions UTC hours
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
                let isActive = start <= end
                    ? (utcHour >= start && utcHour < end)
                    : (utcHour >= start || utcHour < end);

                const el = document.getElementById(session);
                const sessionClass = session.toLowerCase();

                if (isActive) {
                    el.classList.add("active", sessionClass);
                    active = session;
                } else {
                    el.classList.remove("active", sessionClass);
                }
            }

            document.getElementById("pairs").textContent =
                active ? `Best Pairs: ${pairsBySession[active]}` : "Best Pairs: -";
        }

        function getNextSession(utcHour) {
            let sorted = Object.entries(sessions).map(([name, [start]]) => ({ name, start }));
            sorted.sort((a, b) => a.start - b.start);

            for (let s of sorted) {
                if (utcHour < s.start) return s;
            }
            return sorted[0]; // wrap around next day
        }

        function updateCountdown() {
            const now = new Date();
            const utcHour = now.getUTCHours();
            const next = getNextSession(utcHour);

            let nextStart = new Date(Date.UTC(now.getUTCFullYear(), now.getUTCMonth(), now.getUTCDate(), next.start, 0, 0));
            if (next.start <= utcHour) {
                nextStart.setUTCDate(nextStart.getUTCDate() + 1);
            }

            let diff = Math.floor((nextStart - now) / 1000);
            let hours = Math.floor(diff / 3600);
            let minutes = Math.floor((diff % 3600) / 60);
            let seconds = diff % 60;

            document.getElementById("countdown").textContent =
                `Next Session: ${next.name} opens in ${hours}h ${minutes}m ${seconds}s`;
        }

        setInterval(highlightSession, 60000);
        highlightSession();

        setInterval(updateCountdown, 1000);
        updateCountdown();
    </script>
</body>

</html>
