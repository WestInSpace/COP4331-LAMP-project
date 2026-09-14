# Frontend Documentation

## Current Status

The frontend is built using standard HTML5, CSS3, and vanilla JavaScript. It currently relies on static template markup and hardcoded data to render the UI layout, contact cards, and administrative controls.

### Implemented Features

#### Pages :  
    1. login
    2. register
    3. contacts (must be opened manually in an html view and can't navigate to from index.html)


* **Dashboard Layout:** A centered card-based container (`dashboard-card`) with a sticky footer and control headers.
* **Inline Field Copying:** Contact fields include adjacent buttons that write values to `navigator.clipboard`. The button toggles between copy and checkmark SVG icons for 1 second upon success.
* **Custom Dropdown Component:** A custom `<div>`-based dropdown replacing native `<select>` elements to allow consistent styling and rounded corners across browsers.
* **CSS Architecture:** Styles are organized using CSS custom variables (`:root`) for color palettes, spacing, and border radii.
* **Placeholder Mock Data:** Hardcoded contact cards, contact details, owner tags, and dropdown option values are currently embedded directly in `contacts.html` for layout testing.

---

## Required Next Steps

### 1. Remove Placeholder Data & Integrate API
* **Purge Static Content:** Remove all hardcoded contact cards, static owner tags, and fixed ID numbers from `contacts.html`.
* **Dynamic Rendering:** Connect `contacts.js` to `api.js` to fetch real contact lists from the backend REST API and render card nodes dynamically at runtime.
* **Dynamic Dropdown Options:** Replace static admin filter options with data fetched from the active user list endpoint.

### 2. CRUD Operations & Modals
* **Add Contact:** Wire the `+ Add Contact` button to open an interactive creation modal/form and send `POST` requests.
* **Edit Contact:** Enable field editing for existing cards and send `PUT`/`PATCH` updates to the server.
* **Delete Contact:** Attach delete confirmation modals to the delete action buttons and send corresponding `DELETE` requests.

### 3. Real-Time Search & Filtering
* Bind input listeners to `#contact-search` to filter visible contact cards based on name, email, or phone matching.
* Connect the custom filter dropdown selection to adjust query parameters or client-side filtering by contact owner.

### 4. Authentication & Session Management
* Implement page-load session checks to redirect unauthenticated requests to the login view.
* Attach logout handlers to flush stored auth tokens/cookies and handle post-logout navigation.