document.addEventListener("DOMContentLoaded", () => {
  const customSelect = document.querySelector(".custom-select");

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

        const selectedValue = option.getAttribute("data-value");
        console.log("Selected user filter:", selectedValue);
      });
    });

    // Close when clicking outside
    document.addEventListener("click", (e) => {
      if (!customSelect.contains(e.target)) {
        customSelect.classList.remove("open");
      }
    });
  }
});