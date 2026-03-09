let currentUser = null;
let isAdmin = false;

async function getCurrentUser() {
  const res = await fetch("api/get_user.php");
  const data = await res.json();

  if (!data.username) {
    window.location.href = "login.html"; // Not logged in
  } else {
    currentUser = data.username;
    isAdmin = data.is_admin == 1; // check admin flag
    document.getElementById("user-name").textContent = "👤 " + currentUser;

    // ✅ Show Admin Dashboard link if admin
    if (isAdmin && !document.getElementById("admin-link")) {
      const adminLink = document.createElement("a");
      adminLink.href = "admin.html";
      adminLink.id = "admin-link";
      adminLink.textContent = "🛠 Admin Dashboard";
      adminLink.style.marginLeft = "15px";

      document.querySelector("nav").appendChild(adminLink);
    }
  }
}

async function loadQuestions(keyword = "") {
  const res = await fetch(
    "api/get_questions.php" +
      (keyword ? `?search=${encodeURIComponent(keyword)}` : "")
  );
  const questions = await res.json();
  const container = document.getElementById("questions-container");
  container.innerHTML = "";
  questions.forEach((q) => {
    const div = document.createElement("div");
    div.className = "question";
    div.innerHTML = `
      <h3>${q.title}</h3>
      <p>${q.body}</p>
      <small>By ${q.author} | ${new Date(q.date).toLocaleString()}</small><br>
      <button onclick="toggleReplies(${
        q.id
      }, this)" style = "background: none;; color:cyan; border:none; font-size:14px; cursor:pointer;  box-shadow: none;">Show Replies</button>
      <button onclick="deleteQuestion(${
        q.id
      })" style="background: none; color:red; border:none; font-size:14px; cursor:pointer padding-top: 10px; box-shadow: none;">Delete</button>
      <div class="replies" id="replies-${q.id}" style="display:none"></div>
      <div class="reply-form">
        <textarea placeholder="Your reply" id="reply-body-${
          q.id
        }"></textarea> <br>
        <input placeholder="Your name" id="reply-author-${q.id}">
        <button onclick="postReply(${q.id})">Reply</button>
      </div>
    `;
    container.appendChild(div);
  });
}

async function postQuestion() {
  const title = document.getElementById("q-title").value.trim();
  const body = document.getElementById("q-body").value.trim();
  if (!title || !body || !currentUser) return;

  await fetch("api/post_question.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ title, body, author: currentUser }),
  });

  document.getElementById("q-title").value = "";
  document.getElementById("q-body").value = "";
  loadQuestions();
}

async function toggleReplies(id, btn) {
  const container = document.getElementById("replies-" + id);
  if (container.style.display === "none") {
    const res = await fetch("api/get_replies.php?id=" + id);
    const replies = await res.json();
    container.innerHTML = replies
      .map(
        (r) =>
          `<div class="reply"><p>${r.body}</p><small>By ${
            r.author
          } | ${new Date(r.date).toLocaleString()}</small></div>`
      )
      .join("");
    container.style.display = "block";
    btn.textContent = "Hide Replies";
  } else {
    container.style.display = "none";
    btn.textContent = "Show Replies";
  }
}

async function postReply(id) {
  const body = document.getElementById("reply-body-" + id).value.trim();
  const author = document.getElementById("reply-author-" + id).value.trim();
  if (!body || !author) return;

  await fetch("api/post_reply.php?id=" + id, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ body, author }),
  });

  toggleReplies(
    id,
    document.querySelector(`#replies-${id}`).previousElementSibling
  );
}

async function deleteQuestion(id) {
  if (!confirm("Are you sure you want to delete this question?")) return;
  await fetch("api/delete_question.php?id=" + id);
  loadQuestions();
}

function searchQuestions() {
  const keyword = document.getElementById("search-input").value;
  loadQuestions(keyword);
}

document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("post-question-btn")
    .addEventListener("click", postQuestion);
  getCurrentUser();
  loadQuestions();
});

// Dropdown toggle logic
document.addEventListener("click", function (e) {
  const dropdown = document.getElementById("dropdown-menu");
  const trigger = document.getElementById("user-name");

  if (trigger && trigger.contains(e.target)) {
    dropdown.classList.toggle("hidden");
  } else if (!dropdown.contains(e.target)) {
    dropdown.classList.add("hidden");
  }
});

function logout() {
  alert("You've been logged out (mock)");
}

async function logout() {
  await fetch("api/logout.php");
  window.location.href = "index.html";
}
