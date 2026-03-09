window.onload = () => {
  loadStats();
  loadUsers();
  loadQuestions();
  loadResources();
  loadHelps();
};

function logout() {
  fetch("api/logout.php").then(() => (window.location.href = "login.html"));
}

// Inside admin.js or a <script> in admin.html
fetch("api/session.php")
  .then((res) => res.json())
  .then((data) => {
    if (!data.logged_in || !data.is_admin) {
      window.location.href = "login.html"; // Block non-admins
    }
  });

async function loadStats() {
  const res = await fetch("api/admin/stats.php");
  const data = await res.json();
  document.getElementById("stats").innerHTML = `
    Users: ${data.users} | Questions: ${data.questions} | Resources: ${data.resources} | Help Requests: ${data.helps}
  `;
}

async function loadUsers() {
  const res = await fetch("api/admin/users.php");
  const users = await res.json();
  const container = document.getElementById("users");
  container.innerHTML = users
    .map(
      (u) =>
        `<div>${u.username} (${u.email}) 
      <button onclick="deleteUser(${u.id})">Delete</button>
    </div>`
    )
    .join("");
}

async function loadQuestions() {
  const res = await fetch("api/admin/questions.php");
  const data = await res.json();
  const container = document.getElementById("questions");
  container.innerHTML = data
    .map(
      (q) =>
        `<div>${q.title} by ${q.author}
      <button onclick="deleteQuestion(${q.id})">Delete</button>
    </div>`
    )
    .join("");
}

async function loadResources() {
  const res = await fetch("api/admin/resources.php");
  const data = await res.json();
  const container = document.getElementById("resources");
  container.innerHTML = data
    .map(
      (r) =>
        `<div>${r.title} (${r.filename})
      <button onclick="deleteResource(${r.id})">Delete</button>
    </div>`
    )
    .join("");
}

async function loadHelps() {
  const res = await fetch("api/admin/help_requests.php");
  const data = await res.json();
  const container = document.getElementById("helps");
  container.innerHTML = data
    .map(
      (h) =>
        `<div>${h.subject} |  from ${h.username}
      <button onclick="resolveHelp(${h.id})">Resolve</button>
    </div>`
    )
    .join("");
}

async function deleteUser(id) {
  await fetch(`api/admin/delete_user.php?id=${id}`);
  loadUsers();
}

async function deleteQuestion(id) {
  await fetch(`api/admin/delete_question.php?id=${id}`);
  loadQuestions();
}

async function deleteResource(id) {
  await fetch(`api/admin/delete_resource.php?id=${id}`);
  loadResources();
}

async function resolveHelp(id) {
  await fetch(`api/admin/resolve_help.php?id=${id}`);
  loadHelps();
}
