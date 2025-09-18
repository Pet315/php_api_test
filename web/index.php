<?php
require_once __DIR__ . '/config.php';
?>

<!doctype html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Api App</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        input[type=text], input[type=email] { padding: 6px; width: 300px; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Users</h1>
    <div id="error" class="error"></div>
    <table id="users-table">
        <thead><tr><th>ID</th><th>Name</th><th>Email</th></tr></thead>
        <tbody></tbody>
    </table>

    <h2>Add user</h2>
    <form id="addForm">
        <label>Name: <input type="text" name="name" required></label><br><br>
        <label>Email: <input type="email" name="email" required></label><br><br>
        <button type="submit">Add</button>
    </form>

    <script>
    const API_BASE = '<?php echo addslashes(API_BASE); ?>';
    const TOKEN = '<?php echo addslashes(API_TOKEN); ?>';

    function escapeHtml(s) {
        if (!s) return '';
        return s.replace(/[&<>"']/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#039;"}[c]; });
    }

    async function loadUsers() {
        document.getElementById('error').textContent = '';
        try {
            const res = await fetch(API_BASE + '/info', {
                headers: { 'Authorization': 'Bearer ' + TOKEN }
            });
            if (res.status === 401) {
                document.getElementById('error').textContent = 'Unauthorized — invalid token.';
                return;
            }
            const json = await res.json();
            const tbody = document.querySelector('#users-table tbody');
            tbody.innerHTML = '';
            (json.data || []).forEach(u => {
                const tr = document.createElement('tr');
                tr.innerHTML = '<td>' + escapeHtml(String(u.id)) + '</td>'
                             + '<td>' + escapeHtml(u.name) + '</td>'
                             + '<td>' + escapeHtml(u.email) + '</td>';
                tbody.appendChild(tr);
            });
        } catch (e) {
            document.getElementById('error').textContent = 'Network or server error.';
        }
    }

    document.getElementById('addForm').addEventListener('submit', async function(e){
        e.preventDefault();
        document.getElementById('error').textContent = '';
        const form = e.target;
        const data = {
            name: form.name.value,
            email: form.email.value
        };
        try {
            const res = await fetch(API_BASE + '/info', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + TOKEN
                },
                body: JSON.stringify(data)
            });
            const json = await res.json();
            if (!res.ok) {
                document.getElementById('error').textContent = (json.error || 'Error') + (json.messages ? (': ' + json.messages.join('; ')) : '');
                return;
            }
            form.name.value = '';
            form.email.value = '';
            await loadUsers();
        } catch (e) {
            document.getElementById('error').textContent = 'Network or server error.';
        }
    });

    loadUsers();
    </script>
</body>
</html>
