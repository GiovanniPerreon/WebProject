# Development Gameplan - Spotted Unibo Cesena

**Project Vision:** Transform the platform into a Reddit-style forum optimized for "Spotted" posts with enhanced moderation, user interaction, and accessibility.

---

## Table of Contents
- [Team Responsibilities](#team-responsibilities)
- [Core Features to Implement](#core-features-to-implement)
- [Development Phases](#development-phases)
- [Feature Breakdown by Team Member](#feature-breakdown-by-team-member)
- [Technical Specifications](#technical-specifications)
- [Timeline and Milestones](#timeline-and-milestones)

---

## Team Responsibilities

### HTML Developer
**Focus:** Semantic markup, structure, accessibility

**Responsibilities:**
- Create semantic HTML5 structure for new features
- Implement ARIA labels and accessibility attributes
- Ensure proper heading hierarchy
- Add skip links and landmark regions
- Structure forms with proper labels and fieldsets
- Test with screen readers
- Validate HTML markup

**Tools:**
- HTML5 semantic elements
- ARIA attributes
- W3C Validator
- WAVE accessibility tool

---

### PHP/Backend Developer
**Focus:** Server-side logic, database, authentication

**Responsibilities:**
- Database schema modifications
- User registration system
- Tag management CRUD operations
- Direct message functionality
- Admin pin feature
- Password hashing implementation
- Session management
- API endpoints for AJAX requests

**Tools:**
- PHP 7.4+
- MySQL/MariaDB
- Prepared statements
- Password hashing (`password_hash()`)

---

### CSS & JavaScript Developer (You)
**Focus:** Styling, interactivity, user experience

**Responsibilities:**
- Complete CSS redesign (Reddit-style)
- Responsive design across all devices
- Interactive UI components (buttons, modals, dropdowns)
- AJAX implementations (share, DM request, admin actions)
- Form validation
- Loading states and user feedback
- Accessibility styling (focus states, contrast)

**Tools:**
- CSS3 (Flexbox, Grid, Variables)
- Vanilla JavaScript (ES6+)
- Fetch API for AJAX
- No frameworks (vanilla only)

---

## Core Features to Implement

### 1. Tag Management System
**Goal:** Allow admins to create, edit, and delete tags. Users can filter posts by tags.

**Components:**
- Admin panel for tag CRUD operations
- Tag autocomplete/selection in post creation
- Tag cloud/list in sidebar
- Tag filtering page improvements

**Priority:** HIGH

---

### 2. Useful Spotted-Specific Buttons
**Goal:** Replace generic social media buttons with Spotted-specific actions.

**Current Buttons to Replace/Modify:**
- ❌ **Remove:** Generic "Like" button (superfluous for Spotted)
- ✅ **Keep:** Comment functionality
- ✅ **Add:** Share button (local link copy to clipboard)
- ✅ **Add:** DM Request to Author button
- ✅ **Add:** Admin Pin Post button (sticky posts)

**New Button Layout:**
```
┌─────────────────────────────────────────────┐
│  💬 Commenta  |  📩 Contatta Autore  |      │
│  🔗 Condividi |  📌 Pin (admin only)        │
└─────────────────────────────────────────────┘
```

**Priority:** HIGH

---

### 3. Complete CSS Redesign
**Goal:** Create a clean, Reddit-style interface from scratch.

**Design Principles:**
- **Reddit-inspired layout:** Compact post cards, clear hierarchy
- **Dark theme by default:** Keep existing palette, refine execution
- **Mobile-first responsive:** Touch-friendly, optimized for phones
- **Consistent spacing:** Use CSS variables for margins/padding
- **Clear typography:** Readable fonts, good line-height
- **Accessibility:** High contrast, visible focus states

**Key Pages to Redesign:**
1. Homepage (post list)
2. Single post view
3. Tag filter page
4. Archive page
5. User profile
6. Admin panel
7. Login/Registration

**Priority:** HIGH

---

### 4. User Registration System
**Goal:** Allow users to self-register instead of manual account creation.

**Features:**
- Registration form (username, email, password, display name)
- Email validation (format check)
- Password strength requirements
- Password confirmation field
- Terms of service checkbox
- Email verification (optional, future enhancement)
- Account activation by admin (prevent spam)

**Security Requirements:**
- Password hashing with `password_hash()`
- CSRF protection
- Rate limiting (prevent spam registrations)
- Input sanitization

**Priority:** MEDIUM-HIGH

---

### 5. HTML Accessibility (a11y)
**Goal:** Make the site usable for everyone, including users with disabilities.

**Requirements:**
- **ARIA Labels:** All interactive elements properly labeled
- **Keyboard Navigation:** Full site navigable without mouse
- **Focus Management:** Visible focus indicators, logical tab order
- **Skip Links:** Jump to main content, navigation
- **Semantic HTML:** Proper headings, landmarks, lists
- **Alt Text:** All images have descriptive alt attributes
- **Color Contrast:** WCAG AA minimum (4.5:1 for text)
- **Screen Reader Testing:** Test with NVDA/JAWS
- **Form Labels:** All inputs have associated labels

**Accessibility Checklist:**
- [ ] Semantic HTML5 elements (header, nav, main, article, aside, footer)
- [ ] ARIA landmarks and roles
- [ ] Skip to main content link
- [ ] Keyboard-accessible dropdowns and modals
- [ ] Focus visible on all interactive elements
- [ ] Alt text for images
- [ ] Form labels and error messages
- [ ] Color contrast meets WCAG AA
- [ ] Responsive text sizing (rem units)
- [ ] Screen reader testing completed

**Priority:** MEDIUM

---

### 6. Direct Message Request Feature
**Goal:** Allow users to request contact with anonymous post authors.

**How It Works:**
1. User clicks "📩 Contatta Autore" on a post
2. If post is anonymous:
   - Opens modal: "Vuoi richiedere il contatto con l'autore?"
   - User can add optional message explaining why
   - Request sent to post author (notification)
3. Author receives notification (in-app, email optional)
4. Author can:
   - **Accept:** Share their contact info (email or username)
   - **Decline:** Politely decline with optional reason
   - **Close Request:** Mark as resolved

**Database Tables Needed:**
```sql
CREATE TABLE dm_request (
    idrequest INT PRIMARY KEY AUTO_INCREMENT,
    post INT NOT NULL,
    requester INT NOT NULL,
    message TEXT,
    status ENUM('pending', 'accepted', 'declined', 'closed'),
    created_at DATETIME,
    FOREIGN KEY (post) REFERENCES post(idpost),
    FOREIGN KEY (requester) REFERENCES utente(idutente)
);
```

**Priority:** MEDIUM

---

### 7. Share Button (Local Scope)
**Goal:** Allow users to copy post link to clipboard for sharing.

**Implementation:**
- Button: "🔗 Condividi"
- On click: Copy `http://localhost/WebProject/post.php?id=X` to clipboard
- Show toast notification: "Link copiato!"
- Use Clipboard API (modern browsers)
- Fallback for older browsers

**JavaScript Example:**
```javascript
async function sharePost(postId) {
    const url = `${window.location.origin}/WebProject/post.php?id=${postId}`;
    try {
        await navigator.clipboard.writeText(url);
        showToast('Link copiato negli appunti!');
    } catch (err) {
        // Fallback for older browsers
        fallbackCopyToClipboard(url);
    }
}
```

**Priority:** LOW (easy to implement)

---

### 8. Admin Pin Feature
**Goal:** Admins can pin important posts to the top of the homepage.

**Features:**
- "📌 Pin" button visible only to admins
- Pinned posts show at top of homepage with pin indicator
- Maximum 3 pinned posts (configurable)
- Click pin again to unpin
- Pinned posts highlighted visually (border, background color)

**Database Changes:**
```sql
ALTER TABLE post ADD COLUMN pinned TINYINT DEFAULT 0;
ALTER TABLE post ADD COLUMN pin_order INT DEFAULT 0;
```

**Visual Indicator:**
```
┌───────────────────────────────────────────┐
│ 📌 POST PINNED (Importante)               │
│ ┌───────────────────────────────────────┐ │
│ │ Post title here...                    │ │
│ │ Preview text...                       │ │
│ └───────────────────────────────────────┘ │
└───────────────────────────────────────────┘
```

**Priority:** MEDIUM

---

## Development Phases

### Phase 1: Foundation & Setup (Week 1)
**Goal:** Set up infrastructure for new features

**Tasks:**
- [ ] Database schema updates (tags, dm_request, pinned posts)
- [ ] HTML structure review and semantic improvements
- [ ] CSS reset and variable system setup
- [ ] JavaScript module structure for new features

**Team Assignments:**
- **HTML Dev:** Semantic HTML audit, create accessibility baseline
- **PHP Dev:** Database migrations, password hashing implementation
- **CSS/JS Dev:** CSS architecture planning, design system setup

---

### Phase 2: User Registration (Week 2)
**Goal:** Enable user self-registration

**Tasks:**
- [ ] Registration form HTML (semantic, accessible)
- [ ] PHP registration handler with validation
- [ ] Password hashing and security
- [ ] Email validation
- [ ] Registration form styling (CSS)
- [ ] Client-side form validation (JS)
- [ ] Success/error feedback

**Team Assignments:**
- **HTML Dev:** Registration form structure, labels, ARIA
- **PHP Dev:** Backend validation, database insertion, email checks
- **CSS/JS Dev:** Form styling, real-time validation, UX feedback

---

### Phase 3: Tag Management (Week 2-3)
**Goal:** Complete tag system overhaul

**Tasks:**
- [ ] Admin tag CRUD interface (HTML)
- [ ] Tag creation/edit/delete endpoints (PHP)
- [ ] Tag management page styling (CSS)
- [ ] Tag autocomplete in post form (JS)
- [ ] Tag filtering improvements (PHP + JS)
- [ ] Tag cloud/sidebar redesign (CSS)

**Team Assignments:**
- **HTML Dev:** Admin tag management form, accessible controls
- **PHP Dev:** Tag CRUD operations, validation, duplicate prevention
- **CSS/JS Dev:** Tag UI components, autocomplete, filtering UX

---

### Phase 4: Button Redesign (Week 3)
**Goal:** Replace generic buttons with Spotted-specific actions

**Tasks:**
- [ ] Remove like button functionality
- [ ] Implement share button (clipboard copy)
- [ ] Add DM request button and modal
- [ ] Add admin pin button
- [ ] Update button layout and styling
- [ ] AJAX handlers for all new buttons

**Team Assignments:**
- **HTML Dev:** Button structure, modal markup, accessibility
- **PHP Dev:** DM request backend, pin post logic, API endpoints
- **CSS/JS Dev:** Button styling, modal interactions, AJAX, toast notifications

---

### Phase 5: CSS Redesign (Week 4-5)
**Goal:** Complete visual overhaul to Reddit-style

**Tasks:**
- [ ] Design system: colors, typography, spacing
- [ ] Homepage post list redesign
- [ ] Single post page redesign
- [ ] Navigation and header redesign
- [ ] Sidebar redesign
- [ ] Form styling consistency
- [ ] Admin panel styling
- [ ] Mobile responsive refinements
- [ ] Dark theme polish

**Team Assignments:**
- **HTML Dev:** Support with semantic structure as needed
- **PHP Dev:** Support with dynamic content rendering
- **CSS/JS Dev:** Full CSS rewrite, component library, responsive design

---

### Phase 6: Accessibility (Week 5-6)
**Goal:** Ensure WCAG AA compliance

**Tasks:**
- [ ] Add skip links
- [ ] ARIA labels for all interactive elements
- [ ] Keyboard navigation improvements
- [ ] Focus management (modals, dropdowns)
- [ ] Color contrast audit and fixes
- [ ] Alt text for all images
- [ ] Screen reader testing
- [ ] Accessibility documentation

**Team Assignments:**
- **HTML Dev:** LEAD - Semantic HTML, ARIA, keyboard nav, testing
- **PHP Dev:** Support with dynamic content accessibility
- **CSS/JS Dev:** Focus styles, color contrast, keyboard interactions

---

### Phase 7: DM Request System (Week 6)
**Goal:** Implement author contact system

**Tasks:**
- [ ] DM request database table
- [ ] Request submission form and modal
- [ ] Notification system for authors
- [ ] Accept/decline interface
- [ ] Request management page
- [ ] Email notifications (optional)

**Team Assignments:**
- **HTML Dev:** Request modal, notification structure
- **PHP Dev:** LEAD - Request logic, notifications, status management
- **CSS/JS Dev:** Modal interactions, AJAX submission, UX feedback

---

### Phase 8: Admin Pin Feature (Week 7)
**Goal:** Allow admins to pin posts

**Tasks:**
- [ ] Database column for pinned status
- [ ] Pin/unpin button (admin only)
- [ ] Homepage query modification (pinned first)
- [ ] Visual indicator for pinned posts
- [ ] Pin management interface

**Team Assignments:**
- **HTML Dev:** Pin indicator markup
- **PHP Dev:** LEAD - Pin logic, query modifications, permissions
- **CSS/JS Dev:** Pin button styling, AJAX pin/unpin, visual indicators

---

### Phase 9: Testing & Polish (Week 8)
**Goal:** Bug fixes, optimization, final testing

**Tasks:**
- [ ] Cross-browser testing (Chrome, Firefox, Safari, Edge)
- [ ] Mobile device testing (iOS, Android)
- [ ] Performance optimization
- [ ] Security audit
- [ ] Accessibility final testing
- [ ] User acceptance testing
- [ ] Bug fixes
- [ ] Documentation updates

**Team Assignments:**
- **All:** Testing, bug fixes, documentation

---

## Feature Breakdown by Team Member

### HTML Developer - Priority Tasks

#### High Priority
1. **Registration Form Structure**
   - Semantic form with fieldsets and legends
   - Proper label associations
   - ARIA error messages
   - Password visibility toggle button

2. **Accessibility Baseline**
   - Skip to main content link
   - ARIA landmarks on all pages
   - Heading hierarchy audit
   - Keyboard navigation testing

3. **Tag Management Interface**
   - Admin tag CRUD form
   - Tag selection interface (checkboxes/autocomplete)
   - Accessible modal dialogs

#### Medium Priority
4. **DM Request Modal**
   - Accessible modal structure
   - Focus trapping
   - ARIA labels

5. **Alt Text Audit**
   - All images have descriptive alt text
   - Decorative images marked as such

---

### PHP Developer - Priority Tasks

#### High Priority
1. **User Registration Backend**
   - Registration form handler
   - Password hashing with `password_hash()`
   - Email validation and duplicate check
   - Username validation
   - Insert new user into database
   - Success/error handling

2. **Password Hashing Migration**
   - Update login to use `password_verify()`
   - Hash existing passwords (one-time migration)
   - Update user creation to hash passwords

3. **Tag Management CRUD**
   - Create tag
   - Edit tag
   - Delete tag (check for posts using it)
   - Get all tags (with post counts)
   - Tag validation (no duplicates)

#### Medium Priority
4. **DM Request System**
   - Create `dm_request` table
   - Submit DM request endpoint
   - Get requests for user
   - Accept/decline request handlers
   - Notification system (in-app)

5. **Admin Pin Feature**
   - Pin/unpin post endpoint
   - Modify homepage query (ORDER BY pinned DESC, datapost DESC)
   - Pin order management (max 3 pins)

6. **API Endpoints for AJAX**
   - Share post (return formatted link)
   - Pin post (toggle pinned status)
   - DM request submission
   - Tag autocomplete search

---

### CSS & JavaScript Developer - Priority Tasks

#### High Priority
1. **Complete CSS Redesign**
   - CSS variables for colors, spacing, typography
   - Reddit-style post cards (compact, clean)
   - Responsive grid layout
   - Typography system (headings, body, meta)
   - Button component library
   - Form styling (inputs, textareas, checkboxes)
   - Modal component styling
   - Toast notification styling

2. **Button Redesign & Interactions**
   - Remove like button
   - Style new buttons (Commenta, Contatta Autore, Condividi, Pin)
   - Share button with clipboard copy
   - Toast notification system
   - Loading states for buttons

3. **Tag UI Components**
   - Tag badges/pills styling
   - Tag cloud/list sidebar
   - Tag autocomplete dropdown (if implemented)
   - Tag filter page redesign

#### Medium Priority
4. **Form Validation (JavaScript)**
   - Registration form validation
   - Real-time feedback (password strength, email format)
   - Error message display
   - Success feedback

5. **AJAX Implementations**
   - Share button (clipboard API)
   - DM request modal and submission
   - Admin pin/unpin (toggle without reload)
   - Comment submission (AJAX, append to list)

6. **Accessibility Styling**
   - Focus visible styles (outline, ring)
   - High contrast mode support
   - Keyboard navigation indicators
   - Color contrast fixes (WCAG AA)

#### Low Priority
7. **UX Enhancements**
   - Smooth scrolling
   - Loading spinners
   - Skeleton screens
   - Hover effects and transitions
   - Mobile touch optimizations

---

## Technical Specifications

### Database Schema Changes

#### New Tables

**1. dm_request (Direct Message Requests)**
```sql
CREATE TABLE dm_request (
    idrequest INT PRIMARY KEY AUTO_INCREMENT,
    post INT NOT NULL,
    requester INT NOT NULL,
    message TEXT,
    status ENUM('pending', 'accepted', 'declined', 'closed') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (post) REFERENCES post(idpost) ON DELETE CASCADE,
    FOREIGN KEY (requester) REFERENCES utente(idutente) ON DELETE CASCADE
);
```

#### Table Modifications

**1. post (Add pinned feature)**
```sql
ALTER TABLE post
ADD COLUMN pinned TINYINT DEFAULT 0,
ADD COLUMN pin_order INT DEFAULT 0;
```

**2. utente (Enhance registration)**
```sql
ALTER TABLE utente
ADD COLUMN email VARCHAR(255) UNIQUE,
ADD COLUMN email_verified TINYINT DEFAULT 0,
ADD COLUMN created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN last_login DATETIME;
```

---

### API Endpoints for AJAX

**Base URL:** `api-*.php` files

#### POST /api-share.php
**Purpose:** Generate shareable link
**Request:**
```json
{
    "idpost": 123
}
```
**Response:**
```json
{
    "success": true,
    "link": "http://localhost/WebProject/post.php?id=123"
}
```

#### POST /api-pin.php
**Purpose:** Pin/unpin post (admin only)
**Request:**
```json
{
    "idpost": 123,
    "action": "pin" // or "unpin"
}
```
**Response:**
```json
{
    "success": true,
    "pinned": true,
    "message": "Post pinnato con successo"
}
```

#### POST /api-dm-request.php
**Purpose:** Submit DM request to post author
**Request:**
```json
{
    "idpost": 123,
    "message": "Ciao, vorrei maggiori informazioni..."
}
```
**Response:**
```json
{
    "success": true,
    "message": "Richiesta inviata all'autore"
}
```

#### GET /api-tags.php
**Purpose:** Get tags for autocomplete
**Query:** `?q=tech`
**Response:**
```json
{
    "tags": [
        {"idtag": 1, "nometag": "Tecnologia"},
        {"idtag": 5, "nometag": "Tech News"}
    ]
}
```

---

### CSS Architecture

**File Structure:**
```css
/* style.css - Organized sections */

/* 1. CSS Variables */
:root {
    /* Colors */
    --bg-primary: #1e2326;
    --bg-secondary: #2d353b;
    --accent-green: #a7c080;
    --text-primary: #d3c6aa;

    /* Spacing */
    --space-xs: 0.25rem;
    --space-sm: 0.5rem;
    --space-md: 1rem;
    --space-lg: 1.5rem;
    --space-xl: 2rem;

    /* Typography */
    --font-base: 'Segoe UI', sans-serif;
    --font-mono: 'Courier New', monospace;
    --text-xs: 0.75rem;
    --text-sm: 0.875rem;
    --text-base: 1rem;
    --text-lg: 1.125rem;
    --text-xl: 1.25rem;
    --text-2xl: 1.5rem;

    /* Borders */
    --border-radius: 8px;
    --border-color: #4f585e;
}

/* 2. Reset & Base Styles */
/* ... */

/* 3. Layout Components */
/* header, nav, main, aside, footer */

/* 4. UI Components */
/* buttons, forms, modals, cards, badges */

/* 5. Page-Specific Styles */
/* homepage, post detail, profile, admin */

/* 6. Utilities */
/* text utilities, spacing utilities, display utilities */

/* 7. Responsive (Mobile-First) */
@media (min-width: 768px) { /* ... */ }
@media (min-width: 1024px) { /* ... */ }
```

---

### JavaScript Module Structure

**File Organization:**
```
js/
├── main.js              // Core initialization
├── post-actions.js      // Share, DM request, pin
├── comments.js          // Comment submission (AJAX)
├── forms.js             // Form validation
├── modals.js            // Modal management
├── notifications.js     // Toast notifications
└── utils.js             // Utility functions
```

**Example Module Pattern:**
```javascript
// post-actions.js
'use strict';

const PostActions = {
    async sharePost(postId) {
        const url = `${window.location.origin}/WebProject/post.php?id=${postId}`;
        try {
            await navigator.clipboard.writeText(url);
            Notifications.show('Link copiato negli appunti!', 'success');
        } catch (err) {
            console.error('Clipboard error:', err);
            Notifications.show('Errore nel copiare il link', 'error');
        }
    },

    async pinPost(postId, shouldPin) {
        const formData = new FormData();
        formData.append('idpost', postId);
        formData.append('action', shouldPin ? 'pin' : 'unpin');

        try {
            const response = await fetch('api-pin.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();

            if (data.success) {
                Notifications.show(data.message, 'success');
                // Update UI
                this.updatePinButton(postId, data.pinned);
            } else {
                Notifications.show(data.message, 'error');
            }
        } catch (err) {
            console.error('Pin error:', err);
            Notifications.show('Errore nel pinnare il post', 'error');
        }
    },

    updatePinButton(postId, isPinned) {
        const button = document.querySelector(`[data-post-id="${postId}"].pin-btn`);
        if (button) {
            button.textContent = isPinned ? '📌 Unpinned' : '📌 Pin';
            button.dataset.pinned = isPinned;
        }
    }
};

// Initialize when DOM ready
document.addEventListener('DOMContentLoaded', () => {
    // Attach event listeners
    document.querySelectorAll('.share-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const postId = e.target.dataset.postId;
            PostActions.sharePost(postId);
        });
    });

    document.querySelectorAll('.pin-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const postId = e.target.dataset.postId;
            const isPinned = e.target.dataset.pinned === 'true';
            PostActions.pinPost(postId, !isPinned);
        });
    });
});
```

---

## Timeline and Milestones

### Week 1-2: Foundation
- [ ] Database schema updates completed
- [ ] Password hashing implemented
- [ ] HTML accessibility baseline
- [ ] CSS variable system set up
- [ ] User registration backend complete
- [ ] Registration form with validation

**Milestone:** User registration fully functional

---

### Week 3-4: Core Features
- [ ] Tag management system complete
- [ ] Share button implemented
- [ ] DM request system working
- [ ] Admin pin feature functional
- [ ] Button redesign complete (no more like button)

**Milestone:** All new Spotted-specific features working

---

### Week 5-6: Design & Accessibility
- [ ] Complete CSS redesign finished
- [ ] Reddit-style layout implemented
- [ ] Mobile responsive across all pages
- [ ] WCAG AA accessibility compliance
- [ ] Screen reader testing complete

**Milestone:** Site looks professional and is accessible

---

### Week 7-8: Testing & Polish
- [ ] Cross-browser testing complete
- [ ] Mobile device testing complete
- [ ] Security audit complete
- [ ] Performance optimization done
- [ ] All bugs fixed
- [ ] Documentation updated

**Milestone:** Site ready for production/presentation

---

## Success Criteria

### Functional Requirements
✅ Users can self-register
✅ Tags are manageable by admins
✅ Share button copies link to clipboard
✅ DM request system allows anonymous contact
✅ Admins can pin posts to homepage
✅ Like button removed
✅ All AJAX features work without page reload

### Design Requirements
✅ Reddit-style clean layout
✅ Consistent dark theme
✅ Mobile-responsive (works on phones)
✅ Professional appearance
✅ Clear visual hierarchy

### Accessibility Requirements
✅ WCAG AA compliant (color contrast, keyboard nav)
✅ Screen reader compatible
✅ Semantic HTML throughout
✅ All interactive elements accessible
✅ Focus management working

### Security Requirements
✅ Passwords hashed with `password_hash()`
✅ CSRF protection implemented
✅ Input validation and sanitization
✅ SQL injection prevented (prepared statements)
✅ No XSS vulnerabilities

---

## Notes & Reminders

### Communication Protocol
- **Daily standup:** Quick sync on progress (5 min)
- **Weekly review:** Demo completed features
- **Blocker resolution:** Communicate blockers immediately
- **Code review:** Review each other's work before merging

### Git Workflow
- **Branch naming:** `feature/tag-management`, `fix/css-button-alignment`
- **Commit messages:** Descriptive, imperative mood
- **Pull requests:** Required for all features
- **Code review:** At least one team member approval

### Testing Checklist
Before marking a feature complete:
- [ ] Works in Chrome, Firefox, Safari, Edge
- [ ] Works on mobile (iOS and Android)
- [ ] Keyboard accessible
- [ ] Screen reader announces properly
- [ ] No console errors
- [ ] Responsive on all screen sizes
- [ ] Meets design specifications

---

## Questions & Decisions

### Open Questions
1. **Email verification:** Required for registration or optional?
2. **DM notifications:** In-app only or also email?
3. **Max pinned posts:** 3 or configurable?
4. **Anonymous DM:** Can anonymous posters receive DM requests?

### Design Decisions Needed
1. **Color palette:** Keep current dark theme or adjust?
2. **Typography:** Which font family for body text?
3. **Post card layout:** Thumbnail left or top?
4. **Tag display:** Pills, badges, or links?

---

**Document Version:** 1.0
**Last Updated:** January 2024
**Status:** 🚧 Active Planning

---

**Let's build this! 🚀**
