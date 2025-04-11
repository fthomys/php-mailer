<!DOCTYPE html>
<html lang="de" class="">
<head>
    <meta charset="UTF-8">
    <title>Chat Interface Demo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        // Set dark mode based on localStorage
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>
</head>
<body class="h-screen bg-gray-100 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300">

<div class="flex h-full">
    <!-- Userliste -->
    <aside class="w-1/4 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 overflow-y-auto">
        <div class="p-4 border-b dark:border-gray-700">
            <h2 class="text-xl font-bold">Nutzer</h2>
        </div>
        <ul>
            <?php
            $users = ['Anna', 'Max', 'Lisa', 'Tom', 'Julia'];
            foreach ($users as $user): ?>
            <li class="p-4 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                <a href="#"><?= htmlspecialchars($user) ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </aside>

    <!-- Chatbereich -->
    <main class="flex-1 flex flex-col">
        <!-- Header -->
        <div class="p-4 bg-white dark:bg-gray-800 border-b dark:border-gray-700 flex justify-between items-center">
            <h2 class="text-xl font-semibold">Chat mit Anna</h2>
            <!-- Toggle -->
            <label class="flex items-center gap-2 cursor-pointer">
                <span class="text-sm">🌞</span>
                <input type="checkbox" id="themeToggle" class="hidden">
                <div class="w-10 h-5 bg-gray-300 dark:bg-gray-600 rounded-full p-1 flex items-center">
                    <div class="w-4 h-4 bg-white rounded-full shadow transform transition-transform duration-300 ease-in-out translate-x-0 dark:translate-x-5"></div>
                </div>
                <span class="text-sm">🌙</span>
            </label>
        </div>

        <!-- Nachrichtenfenster -->
        <div id="chatBox" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50 dark:bg-gray-900">
            <!-- Beispielnachrichten -->
            <div class="flex justify-start">
                <div class="bg-white dark:bg-gray-700 p-3 rounded-lg shadow max-w-xs">
                    <p>Hi! Wie geht’s?</p>
                    <span class="text-xs text-gray-400 dark:text-gray-300 block text-right">12:01</span>
                </div>
            </div>
            <div class="flex justify-end">
                <div class="bg-blue-500 text-white p-3 rounded-lg shadow max-w-xs">
                    <p>Gut, danke! Und dir?</p>
                    <span class="text-xs text-white/70 block text-right">12:02</span>
                </div>
            </div>
        </div>

        <!-- Nachrichteneingabe -->
        <form id="chatForm" class="p-4 bg-white dark:bg-gray-800 border-t dark:border-gray-700 flex gap-2" onsubmit="return false;">
            <input id="chatInput" type="text" name="message" placeholder="Nachricht schreiben..." class="flex-1 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-black dark:text-white rounded-lg px-4 py-2 focus:outline-none focus:ring focus:border-blue-300" />
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                Senden
            </button>
        </form>
    </main>
</div>

<!-- JS für Chat & Theme -->
<script>
    const chatForm = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatInput');
    const chatBox = document.getElementById('chatBox');

    chatForm.addEventListener('submit', () => {
        const message = chatInput.value.trim();
        if (message === '') return;

        appendMessage('user', message);
        chatInput.value = '';
        chatInput.focus();

        setTimeout(() => {
            appendMessage('bot', getBotResponse(message));
        }, 1000);
    });

    function appendMessage(type, message) {
        const wrapper = document.createElement('div');
        wrapper.className = 'flex ' + (type === 'user' ? 'justify-end' : 'justify-start');

        const bubble = document.createElement('div');
        bubble.className =
            (type === 'user'
                ? 'bg-blue-500 text-white'
                : 'bg-white dark:bg-gray-700 text-black dark:text-white') +
            ' p-3 rounded-lg shadow max-w-xs';

        bubble.innerHTML = `<p>${message}</p><span class="text-xs block text-right ${
            type === 'user' ? 'text-white/70' : 'text-gray-400 dark:text-gray-300'
        }">${getTime()}</span>`;

        wrapper.appendChild(bubble);
        chatBox.appendChild(wrapper);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function getTime() {
        const now = new Date();
        return now.getHours().toString().padStart(2, '0') + ':' +
            now.getMinutes().toString().padStart(2, '0');
    }

    function getBotResponse(userMessage) {
        const responses = [
            "Cool 😎",
            "Erzähl mehr!",
            "Klingt spannend!",
            "Okay, verstehe.",
            "Haha, nice 😂",
            "Bin dabei!",
            "Lass uns das machen 💪"
        ];
        return responses[Math.floor(Math.random() * responses.length)];
    }

    // Theme toggle logic
    const themeToggle = document.getElementById('themeToggle');
    const root = document.documentElement;

    themeToggle.checked = root.classList.contains('dark');

    themeToggle.addEventListener('change', () => {
        if (themeToggle.checked) {
            root.classList.add('dark');
            localStorage.theme = 'dark';
        } else {
            root.classList.remove('dark');
            localStorage.theme = 'light';
        }
    });
</script>

</body>
</html>
