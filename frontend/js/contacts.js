/* COMBINED SEARCH & USER FILTER LOGIC */

document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById("contact-search");
  const customSelect = document.querySelector(".custom-select");

  // Wire up search input listener
  if (searchInput) {
    searchInput.addEventListener("input", filterContacts);
  }

  // Wire up custom select dropdown logic
  if (customSelect) {
    const trigger = customSelect.querySelector(".select-trigger");
    const options = customSelect.querySelectorAll(".custom-option");
    const selectedText = customSelect.querySelector(".selected-text");

    // Toggle dropdown open/closed
    trigger.addEventListener("click", (e) => {
      e.stopPropagation();
      customSelect.classList.toggle("open");
    });

    // Option selection
    options.forEach(option => {
      option.addEventListener("click", () => {
        options.forEach(opt => opt.classList.remove("selected"));
        option.classList.add("selected");
        selectedText.textContent = option.textContent;
        customSelect.classList.remove("open");

        // Re-filter cards with updated owner context
        filterContacts();
      });
    });

    // Close dropdown when clicking outside
    document.addEventListener("click", (e) => {
      if (!customSelect.contains(e.target)) {
        customSelect.classList.remove("open");
      }
    });
  }
});

/**
 * Filters visible contact cards based on text query and owner filter.
 */
function filterContacts() {
  const searchInput = document.getElementById("contact-search");
  const query = searchInput ? searchInput.value.toLowerCase().trim() : "";

  const selectedOption = document.querySelector(".custom-option.selected");
  const selectedOwner = selectedOption ? selectedOption.textContent.toLowerCase().trim() : "all users' contacts";

  const cards = document.querySelectorAll(".contacts-grid .contact-card");

  cards.forEach(card => {
    // Extract contact text fields
    const name = card.querySelector("h3")?.textContent.toLowerCase() || "";
    const emailInput = card.querySelector('input[type="email"]');
    const phoneInput = card.querySelector('input[type="text"]');

    const email = emailInput ? emailInput.value.toLowerCase() : "";
    const phone = phoneInput ? phoneInput.value.toLowerCase() : "";

    // Extract owner text from tag
    const ownerTag = card.querySelector(".owner-tag")?.textContent.toLowerCase() || "";

    // Check search query match
    const matchesSearch = name.includes(query) || email.includes(query) || phone.includes(query);

    // Check owner filter match
    const isAllFilter = selectedOwner.includes("all users");
    const matchesOwner = isAllFilter || ownerTag.includes(selectedOwner);

    // Display card only if both filters pass
    card.style.display = matchesSearch && matchesOwner ? "" : "none";
  });
}

/* ==========================================================================
   CLIPBOARD COPY HANDLER
   ========================================================================== */

document.addEventListener("DOMContentLoaded", () => {
  const contactsGrid = document.getElementById("contacts-grid");

  if (contactsGrid) {
    contactsGrid.addEventListener("click", (e) => {
      const copyBtn = e.target.closest(".btn-copy");
      if (!copyBtn) return;

      const wrapper = copyBtn.closest(".input-with-copy");
      const input = wrapper ? wrapper.querySelector("input") : null;

      if (input && input.value) {
        navigator.clipboard.writeText(input.value).then(() => {
          // Visual feedback
          copyBtn.classList.add("copied");
          
          setTimeout(() => {
            copyBtn.classList.remove("copied");
          }, 1500);
        }).catch(err => {
          console.error("Failed to copy text: ", err);
        });
      }
    });
  }
});

document.addEventListener("DOMContentLoaded", () => {
  const contactsGrid = document.getElementById("contacts-grid");

  if (contactsGrid) {
    contactsGrid.addEventListener("click", (e) => {
      const copyBtn = e.target.closest(".btn-copy-inline");
      if (!copyBtn) return;

      const wrapper = copyBtn.closest(".input-with-copy");
      const input = wrapper ? wrapper.querySelector("input") : null;

      if (input && input.value) {
        navigator.clipboard.writeText(input.value).then(() => {
          // Add copied class to show checkmark SVG
          copyBtn.classList.add("copied");

          // Reset back to original copy SVG after 1 second (1000ms)
          setTimeout(() => {
            copyBtn.classList.remove("copied");
          }, 1000);
        }).catch(err => {
          console.error("Failed to copy text: ", err);
        });
      }
    });
  }
});