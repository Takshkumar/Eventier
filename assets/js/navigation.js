// Navigation Component Loader
document.addEventListener("DOMContentLoaded", function () {
  loadNavigation();
});

function loadNavigation() {
  // Create a placeholder for the navigation
  const navPlaceholder = document.getElementById("navigation-placeholder");

  if (navPlaceholder) {
    // Fetch the navigation component
    fetch("assets/components/navigation.html")
      .then((response) => response.text())
      .then((html) => {
        navPlaceholder.innerHTML = html;

        // Set active page highlighting
        setActivePage();
      })
      .catch((error) => {
        console.error("Error loading navigation:", error);
        // Fallback navigation if fetch fails
        navPlaceholder.innerHTML = getFallbackNavigation();
      });
  }
}

function setActivePage() {
  const currentPage = window.location.pathname.split("/").pop() || "index.html";
  const navLinks = document.querySelectorAll(".nav-link");

  navLinks.forEach((link) => {
    const href = link.getAttribute("href");
    if (href === currentPage || (currentPage === "" && href === "index.html")) {
      link.classList.add("active");
    }
  });
}

function getFallbackNavigation() {
  return `
        <header class="header">
            <div class="logo-section">
                <img src="assets/images/logo.png" alt="Eventier Logo" class="logo-image">
                <h2 class="logo-text">Eventier</h2>
            </div>
            <nav class="nav-menu">
                <div class="nav-links">
                    <a href="index.html" class="nav-link">Home</a>
                    <a href="#" class="nav-link">Events</a>
                    <a href="#" class="nav-link">Explore</a>
                    <a href="about.html" class="nav-link">About Us</a>
                </div>
                <button class="btn btn-primary create-event-btn">Create Event</button>
                <div class="user-profile">
                    <div class="profile-image" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAOYOdAqH2-zYxUcug6KlJLHjqAiqv2wBvvhu_aDChcAGogKkXf9e2dcsXSkhL0Q2Tky2m5QJBzBMrG-EDNMbg7riHnpRMKE_5rziGAWuVrCrFAyBpwbNiHhATHeyV6Kjqjg4HAYpnJLpIYXP3IN2-_9So9MBlRpWvvZghjzDWy-pJn8jAziFmiOqcxi6QYFAaB8K_s_CwV889iQIrPTEsKBz34W6V8cEyn6JvxpEqxkM0SejHtV-6F7zKWkQ7twN1wALMSVMD94g')"></div>
                </div>
            </nav>
        </header>
    `;
}
