document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".btn-toggle-comments").forEach((btn) => {
    btn.addEventListener("click", () => {
      const pubId = btn.getAttribute("data-id");

      // Oculta todos los comentarios
      document.querySelectorAll(".comments-section").forEach((section) => {
        if (section.id !== "comments-" + pubId) {
          section.classList.add("d-none");
        }
      });

      const commentsSection = document.getElementById("comments-" + pubId);
      commentsSection.classList.toggle("d-none");
    });
  });

  document.querySelectorAll(".comment-form").forEach((form) => {
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      const input = form.querySelector('input[type="text"]');
      const commentText = input.value.trim();
      if (!commentText) return;

      const commentsDiv = form.previousElementSibling;
      const p = document.createElement("p");
      p.textContent = commentText;
      p.classList.add("mb-1");
      commentsDiv.appendChild(p);
      input.value = "";
    });
  });
});
