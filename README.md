# Spotted Unibo Cesena

A forum-style web platform for the University of Bologna - Cesena Campus community to share posts, discussions, and campus-related content.

> **Project Status:** 🚧 In Active Development - Features and documentation subject to change

---

## Table of Contents
- [Project Overview](#project-overview)
- [Goals and Objectives](#goals-and-objectives)
- [Current Features](#current-features)
- [Technology Stack](#technology-stack)
- [Architecture and Design Patterns](#architecture-and-design-patterns)
- [Database Schema](#database-schema)
- [Coding Practices and Standards](#coding-practices-and-standards)
- [Installation and Setup](#installation-and-setup)
- [Project Structure](#project-structure)
- [Development Roadmap](#development-roadmap)
- [Known Issues and Limitations](#known-issues-and-limitations)
- [Contributing](#contributing)

---

## Project Overview

**Spotted Unibo Cesena** is a community-driven forum platform designed for University of Bologna students at the Cesena campus. The platform allows users to create and share posts (similar to "spotted" pages on social media), engage through comments and likes, and organize content using tags.

The project follows a simplified forum design philosophy, prioritizing ease of use, clean presentation, and efficient content discovery over complex social media features.

### Project Goals
This project demonstrates practical application of web development technologies in an academic context, focusing on:
- Clean, maintainable code architecture
- Secure database interactions
- Responsive user interface design
- Modern web development best practices

---

## Goals and Objectives

### Primary Goals
1. **Community Engagement:** Provide a centralized platform for campus-related discussions and announcements
2. **Content Organization:** Enable users to categorize and discover posts through a tagging system
3. **User Interaction:** Support basic social features (likes, comments) to encourage engagement
4. **Simplicity:** Maintain a clean, forum-like interface that prioritizes content over complexity
5. **Learning:** Demonstrate understanding of full-stack web development concepts

### Target Audience
- University of Bologna students (Cesena campus)
- Campus community members
- Anyone interested in campus-related discussions

### Final Product Vision
A lightweight, responsive web forum with:
- Clean, dark-themed user interface
- Post creation and management capabilities
- Tag-based content categorization
- Comment threads on posts
- Like/unlike functionality
- Anonymous posting option
- Archive and filtering capabilities
- Mobile-responsive design

---

## Current Features

### Implemented ✅

#### User Management
- Session-based authentication system
- User login and logout
- Admin role differentiation
- User profile pages (in development)

#### Post Management
- Create, edit, and delete posts (authenticated users)
- Rich post content with title, preview, and full text
- Image upload support (JPG, JPEG, PNG, GIF - max 500KB)
- Anonymous posting option (identity visible only to admins)
- Post ownership validation (users can only edit their own posts)

#### Content Organization
- Multi-tag categorization system
- Tag-based filtering
- Archive view of all posts
- Chronological sorting

#### Social Interaction
- Comment system on posts
- Like/unlike functionality with user tracking
- Comment count and like count display
- Author attribution with admin badges

#### User Interface
- Dark theme with forest/terminal aesthetic
- Responsive design (mobile, tablet, desktop)
- Tag navigation sidebar
- Post metadata display (author, date, stats)
- Clean post preview cards

### In Development 🚧

- AJAX-based interactions (eliminate page reloads)
- Enhanced form validation
- Share functionality with clipboard copy
- Report/flag system for content moderation
- Breadcrumb navigation
- Popular posts section
- Archive grouped by month/year
- Search functionality

---

## Technology Stack

### Frontend
- **HTML5:** Semantic markup with PHP templating
- **CSS3:** Custom styling with modern features
  - Flexbox and Grid layouts
  - CSS transitions and animations
  - Dark theme color scheme
  - Responsive breakpoints at 768px
  - CSS variables (planned for consistency)
- **JavaScript (ES6+):** Client-side interactivity
  - Modular file structure
  - DOM manipulation and event handling
  - AJAX for asynchronous operations (in development)
  - Form validation enhancement (planned)

### Backend
- **PHP 7.4+:** Server-side logic and templating
  - Object-oriented DatabaseHelper class
  - Session-based authentication
  - File upload handling
  - Template rendering system
- **MySQL 5.7+:** Relational database
  - InnoDB engine for transactions
  - Foreign key constraints
  - Prepared statements for security
- **Apache 2.4+:** Web server (via XAMPP)

### Development Environment
- **XAMPP:** Local development stack (Apache, MySQL, PHP)
- **Git:** Version control
- **GitHub:** Code repository and collaboration
- **VSCode:** Primary development IDE

### Dependencies
**None** - Project uses vanilla PHP, CSS, and JavaScript with no external frameworks or libraries. This demonstrates understanding of core web technologies without framework abstraction.

---

## Architecture and Design Patterns

### MVC-Inspired Architecture

The application follows a three-layer architecture pattern inspired by Model-View-Controller, adapted for PHP template-based development:

```
┌─────────────────────────────────────────────────┐
│                 USER REQUEST                    │
│           (GET/POST to .php file)               │
└─────────────┬───────────────────────────────────┘
              │
              ▼
┌─────────────────────────────────────────────────┐
│  BOOTSTRAP LAYER (bootstrap.php)                │
│  - Start session                                │
│  - Define constants (UPLOAD_DIR)                │
│  - Load utilities and database helper           │
│  - Create global $dbh instance                  │
└─────────────┬───────────────────────────────────┘
              │
              ▼
┌─────────────────────────────────────────────────┐
│  CONTROLLER LAYER (Root .php files)             │
│  - index.php, post.php, login.php, etc.         │
│  - Authenticate users (if required)             │
│  - Fetch data from Model                        │
│  - Prepare $templateParams array                │
│  - Include view template                        │
└─────────────┬───────────────────────────────────┘
              │
              ▼
┌─────────────────────────────────────────────────┐
│  VIEW LAYER (template/*.php)                    │
│  - base.php: Master HTML skeleton               │
│  - Content templates: lista-posts.php, etc.     │
│  - Access data via $templateParams              │
│  - Generate HTML output                         │
│  - No direct database access                    │
└─────────────┬───────────────────────────────────┘
              │
              ▼
┌─────────────────────────────────────────────────┐
│  MODEL LAYER (db/database.php)                  │
│  - DatabaseHelper class                         │
│  - All database operations                      │
│  - Prepared statements (SQL injection safe)     │
│  - Returns associative arrays                   │
└─────────────────────────────────────────────────┘
```

### Key Design Patterns

#### 1. Bootstrap Pattern
Every page includes `bootstrap.php` which handles:
- Session initialization
- Environment configuration
- Dependency loading
- Database connection setup

**Benefits:** Consistent initialization, DRY principle, centralized configuration

#### 2. Template Pattern
Two-layer template system:
1. Controller sets data in `$templateParams` and includes `base.php`
2. Base template provides layout and includes specific content template

**Benefits:** Consistent layout, reusable components, separation of concerns

#### 3. Post-Redirect-Get (PRG)
Form processing follows PRG pattern:
- Display page shows form
- Processing script handles POST and redirects
- Prevents duplicate submissions on refresh

**Example Flow:**
```
gestisci-posts.php (form) → POST → processa-post.php (process) → REDIRECT → index.php (result)
```

#### 4. Database Abstraction
DatabaseHelper class provides abstraction layer:
- Single connection point
- Consistent query interface
- Prepared statement enforcement
- Error handling centralization

---

## Database Schema

### Entity-Relationship Overview

```
┌─────────────┐         ┌──────────────┐         ┌─────────────┐
│   utente    │1       N│     post     │N       M│     tag     │
│─────────────│◄────────│──────────────│◄────────│─────────────│
│ idutente PK │         │ idpost PK    │         │ idtag PK    │
│ username    │         │ titolopost   │         │ nometag     │
│ password    │         │ testopost    │         └─────────────┘
│ nome        │         │ anteprimapost│
│ amministrat.│         │ imgpost      │
│ attivo      │         │ datapost     │
└─────────────┘         │ likes        │
      │                 │ anonimo      │
      │                 │ utente FK    │
      │                 └──────────────┘
      │                       │
      │1                      │1
      │                       │
      │N                      │N
┌──────────────┐        ┌──────────────┐
│ user_likes   │        │   commento   │
│──────────────│        │──────────────│
│ utente FK    │        │ idcommento PK│
│ post FK      │        │ testocommento│
└──────────────┘        │ datacommento │
                        │ nomeautore   │
                        │ post FK      │
                        └──────────────┘
```

### Tables

#### `utente` (Users)
Stores user account information and authentication credentials.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| idutente | INT | PRIMARY KEY, AUTO_INCREMENT | Unique user identifier |
| username | VARCHAR(100) | NOT NULL | Login username |
| password | VARCHAR(512) | NOT NULL | User password (⚠️ currently plaintext) |
| nome | VARCHAR(45) | NOT NULL | Display name |
| amministratore | TINYINT | DEFAULT 0 | Admin flag (0/1) |
| attivo | TINYINT | DEFAULT 0 | Account active status |

#### `post` (Blog Posts)
Contains all post content and metadata.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| idpost | INT | PRIMARY KEY, AUTO_INCREMENT | Unique post identifier |
| titolopost | VARCHAR(100) | NOT NULL | Post title |
| testopost | MEDIUMTEXT | NOT NULL | Full post content |
| anteprimapost | TINYTEXT | NOT NULL | Preview/excerpt text |
| imgpost | VARCHAR(100) | NOT NULL | Image filename |
| datapost | DATE | NOT NULL | Publication date |
| likes | INT | DEFAULT 0 | Like count |
| anonimo | TINYINT | DEFAULT 0 | Anonymous post flag |
| utente | INT | FOREIGN KEY | Author user ID |

#### `tag` (Categories/Tags)
Defines available content categories.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| idtag | INT | PRIMARY KEY, AUTO_INCREMENT | Unique tag identifier |
| nometag | VARCHAR(50) | NOT NULL, UNIQUE | Tag name |

#### `post_tag` (Many-to-Many Junction)
Links posts to tags (many-to-many relationship).

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| post | INT | FOREIGN KEY, PRIMARY KEY (composite) | Post ID |
| tag | INT | FOREIGN KEY, PRIMARY KEY (composite) | Tag ID |

**Cascade Behavior:** Deleting a post or tag removes corresponding junction records.

#### `commento` (Comments)
Stores user comments on posts.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| idcommento | INT | PRIMARY KEY, AUTO_INCREMENT | Unique comment identifier |
| testocommento | TEXT | NOT NULL | Comment content |
| datacommento | DATETIME | NOT NULL | Comment timestamp |
| nomeautore | VARCHAR(100) | NOT NULL | Comment author name |
| post | INT | FOREIGN KEY | Parent post ID |

**Cascade Behavior:** Deleting a post removes all its comments.

#### `user_likes` (Like Tracking)
Tracks which users liked which posts.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| utente | INT | FOREIGN KEY, PRIMARY KEY (composite) | User who liked |
| post | INT | FOREIGN KEY, PRIMARY KEY (composite) | Post that was liked |

**Cascade Behavior:** Deleting a user or post removes corresponding like records.

### Database Features
- **InnoDB Engine:** ACID compliance, transaction support, foreign key constraints
- **UTF-8 Encoding:** Support for international characters
- **Referential Integrity:** Foreign keys ensure data consistency
- **Cascade Deletes:** Automatic cleanup of dependent records
- **Prepared Statements:** All queries use parameterized statements for security

---

## Coding Practices and Standards

### Security Practices

#### Implemented ✅
- **SQL Injection Prevention:** All queries use prepared statements with parameter binding
  ```php
  $stmt = $this->db->prepare("SELECT * FROM post WHERE idpost=?");
  $stmt->bind_param('i', $id);
  ```
- **Session Management:** Secure session-based authentication
- **File Upload Validation:** Size limits, extension whitelist, MIME type checking
- **Access Control:** User ownership validation for edit/delete operations
- **XSS Prevention:** HTML escaping in output (via `htmlspecialchars` where needed)

#### Known Security Issues ⚠️
- **Password Storage:** Passwords currently stored in plaintext
  - **Planned Fix:** Implement `password_hash()` and `password_verify()`
- **CSRF Protection:** Not yet implemented for forms
  - **Planned Fix:** Add CSRF token validation
- **Input Sanitization:** Could be improved for user-generated content
  - **Planned Fix:** Enhanced validation and sanitization layer

### PHP Best Practices

#### Code Organization
- **MVC Separation:** Clear distinction between data, logic, and presentation
- **DRY Principle:** Reusable functions in `utils/functions.php`
- **Single Responsibility:** Each file has one clear purpose
- **Consistent Naming:** camelCase for variables, lowercase-hyphen for files

#### PHP Standards
```php
// File structure
require_once 'bootstrap.php';  // Always first

// Authentication check for protected pages
if(!isUserLoggedIn()){
    header("location: login.php");
    exit;
}

// Data preparation
$templateParams["key"] = $value;

// Template rendering
require 'template/base.php';  // Always last
```

### CSS Best Practices

#### Methodology
- **Semantic Class Names:** `.post-preview`, `.comment-item`, `.tag-link`
- **BEM-Inspired:** Block-Element-Modifier naming where appropriate
- **Low Specificity:** Avoid over-specific selectors
- **No ID Styling:** IDs reserved for JavaScript, classes for styling

#### Organization
```css
/* File structure in style.css */
1. CSS Reset (margins, padding)
2. Root/Global styles (body, typography)
3. Layout components (header, nav, main, aside, footer)
4. Component styles (buttons, forms, cards)
5. Page-specific styles (with comments)
6. Utility classes
7. Media queries (mobile-first approach)
```

#### Current Color Scheme
```css
/* Dark Theme Palette */
Background Primary:   #1e2326  /* Page background */
Background Secondary: #2d353b  /* Cards, containers */
Background Tertiary:  #272e33  /* Nested elements */
Accent Green:         #a7c080  /* Primary actions, links */
Text Primary:         #d3c6aa  /* Main content text */
Text Muted:           #859289  /* Metadata, hints */
Border:               #4f585e  /* Subtle separators */
Error/Warning:        #e67e80  /* Destructive actions */
Info:                 #7fbbb3  /* Information, share */
```

### JavaScript Best Practices

#### Code Quality Standards
```javascript
'use strict';  // Always use strict mode

// Modern ES6+ features
const element = document.querySelector('.selector');
let counter = 0;

// Arrow functions for callbacks
element.addEventListener('click', (e) => {
    // Handle event
});

// Template literals for strings
const message = `User ${username} logged in`;
```

#### File Organization
- `main.js` - Core initialization and DOMContentLoaded
- `post-actions.js` - Like, share, report functionality
- `comments.js` - Comment submission and display
- `forms.js` - Form validation and file upload

#### Planned Standards
- Event delegation for dynamic content
- AJAX with Fetch API (no jQuery)
- Error handling with try/catch
- Loading states and user feedback
- Debouncing for performance-sensitive operations

### Version Control Practices

#### Git Workflow
```bash
# Atomic commits - one logical change per commit
git add specific-files
git commit -m "Brief description of change"

# Meaningful commit messages
# Format: Action verb + what changed + why (if not obvious)
# Example: "Fix CSS syntax errors and set up JavaScript structure"
```

#### Commit Message Guidelines
- Use imperative mood ("Add feature" not "Added feature")
- Keep first line under 50 characters
- Add detailed bullet points for complex changes
- Reference issue numbers when applicable

### Documentation Standards

#### Code Comments
```php
// Single-line comments for brief explanations
/* Multi-line comments for complex logic */

// Function documentation (planned)
/**
 * Retrieve posts by tag ID
 * @param int $idtag Tag identifier
 * @param int $n Limit number of posts (-1 for all)
 * @return array Associative array of posts
 */
```

#### File Headers
Each module should include purpose description:
```javascript
/**
 * Post Actions Module
 * Handles like, share, and report functionality
 */
```

---

## Installation and Setup

### Prerequisites
- **XAMPP** (or equivalent LAMP/WAMP/MAMP stack)
  - Apache 2.4+
  - MySQL 5.7+
  - PHP 7.4+
- **Git** (for version control)
- **Modern Web Browser** (Chrome, Firefox, Edge, Safari)
- **Text Editor/IDE** (VSCode recommended)

### Step-by-Step Installation

#### 1. Clone Repository
```bash
git clone https://github.com/GiovanniPerreon/WebProject.git
cd WebProject
```

#### 2. Start XAMPP Services
- Open **XAMPP Control Panel**
- Click **Start** next to **Apache**
- Click **Start** next to **MySQL**
- Verify both show green "Running" status

#### 3. Create Database

**Using phpMyAdmin (Recommended):**
1. Navigate to: `http://localhost/phpmyadmin/index.php?route=/server/sql`
2. Copy entire contents of `db/creazione_db.sql`
3. Paste into SQL field and click **Go**
4. Verify `spotted_db` appears in left sidebar
5. Navigate to: `http://localhost/phpmyadmin/index.php?route=/database/sql&db=spotted_db`
6. Copy entire contents of `db/inserisci_dati.sql`
7. Paste and click **Go**
8. Verify tables are populated with sample data

**Using MySQL CLI:**
```bash
mysql -u root -p < db/creazione_db.sql
mysql -u root -p < db/inserisci_dati.sql
```

#### 4. Configure Database Connection
If your MySQL credentials differ from defaults, edit `bootstrap.php`:
```php
$dbh = new DatabaseHelper("localhost", "root", "", "spotted_db", 3306);
//                          host        user  pass  database   port
```

#### 5. Set Permissions
Ensure upload directory is writable:
```bash
# On Linux/Mac
chmod 755 upload/

# On Windows (usually not necessary with XAMPP)
# Verify folder has write permissions
```

#### 6. Access Application
Open web browser and navigate to:
```
http://localhost/WebProject/
```

You should see the homepage with sample posts.

### Default Test Accounts
Check `db/inserisci_dati.sql` for default users and their credentials.

### Troubleshooting

**Problem:** Apache won't start
- **Cause:** Port 80 in use (Skype, IIS, other web server)
- **Solution:** Change Apache port in XAMPP config OR stop conflicting application

**Problem:** MySQL connection errors
- **Cause:** MySQL not running or wrong credentials
- **Solution:**
  1. Verify MySQL is running in XAMPP Control Panel
  2. Check `bootstrap.php` database credentials
  3. Verify database `spotted_db` exists in phpMyAdmin

**Problem:** File upload fails
- **Cause:** Directory permissions or PHP settings
- **Solution:**
  1. Ensure `upload/` directory exists and is writable
  2. Check PHP settings: `upload_max_filesize` and `post_max_size` in `php.ini`

**Problem:** Blank page or PHP errors
- **Cause:** PHP error display disabled
- **Solution:** Enable error reporting in `php.ini`:
  ```ini
  display_errors = On
  error_reporting = E_ALL
  ```

---

## Project Structure

```
WebProject/
│
├── css/
│   └── style.css                 # Main stylesheet (dark theme)
│
├── db/
│   ├── database.php              # DatabaseHelper class
│   ├── creazione_db.sql          # Database schema creation
│   ├── inserisci_dati.sql        # Sample data insertion
│   └── DBSetupTutorial.txt       # Quick setup guide
│
├── js/                           # JavaScript modules
│   ├── main.js                   # Core initialization
│   ├── post-actions.js           # Like, share, report (in dev)
│   ├── comments.js               # Comment handling (in dev)
│   └── forms.js                  # Form validation (in dev)
│
├── template/                     # View templates
│   ├── base.php                  # Master HTML skeleton
│   ├── lista-posts.php           # Post list view
│   ├── singolo-post.php          # Single post detail
│   ├── admin-form.php            # Post create/edit form
│   ├── gestisci-posts-lista.php  # Admin post management
│   ├── login-form.php            # Login form
│   ├── login-home.php            # Logged-in home view
│   └── contatti.php              # Contact page
│
├── upload/                       # User-uploaded images
│   ├── default.jpg               # Placeholder image
│   └── [user-uploads]            # User-generated content
│
├── utils/
│   └── functions.php             # Utility helper functions
│
├── bootstrap.php                 # Application initialization
│
├── index.php                     # Homepage controller
├── post.php                      # Single post controller
├── login.php                     # Login controller
├── logout.php                    # Logout handler
├── gestisci-posts.php            # Post management controller
├── archivio.php                  # Archive controller
├── tag.php                       # Tag filter controller
├── contatti.php                  # Contact controller
├── profilo.php                   # User profile (in dev)
│
├── processa-post.php             # Post CRUD handler
├── processa-commento.php         # Comment submission handler
├── processa-like.php             # Like/unlike handler
│
├── README.md                     # This file
├── CSS_REDESIGN_MOCKUP.md        # UI/UX design documentation
└── .gitignore                    # Git ignore rules
```

---

## Development Roadmap

### Phase 1: Foundation ✅ COMPLETED
- [x] Database schema design
- [x] Basic PHP architecture (MVC pattern)
- [x] User authentication system
- [x] Post CRUD operations
- [x] Comment system
- [x] Like functionality
- [x] Tag system
- [x] File upload handling
- [x] Basic CSS styling (dark theme)
- [x] Responsive layout
- [x] Anonymous posting feature

### Phase 2: Enhancement 🚧 IN PROGRESS
- [x] JavaScript file structure setup
- [ ] AJAX like button (no page reload)
- [ ] AJAX comment submission
- [ ] Share button with clipboard API
- [ ] Report/flag functionality
- [ ] Form validation improvements
- [ ] Loading states and user feedback
- [ ] CSS refinement (forum-style redesign)
- [ ] Breadcrumb navigation
- [ ] User profile pages

### Phase 3: Polish (PLANNED)
- [ ] Password hashing implementation
- [ ] CSRF protection
- [ ] Input sanitization layer
- [ ] Search functionality
- [ ] Archive grouped by month/year
- [ ] Popular posts sidebar
- [ ] Email notifications (optional)
- [ ] Admin dashboard
- [ ] Image optimization
- [ ] Performance optimization

### Phase 4: Advanced (FUTURE)
- [ ] User registration system
- [ ] Profile customization
- [ ] Edit/delete comments
- [ ] Markdown support in posts
- [ ] User reputation system
- [ ] Dark/light mode toggle
- [ ] Advanced moderation tools
- [ ] API endpoints for mobile app
- [ ] Progressive Web App (PWA) features

---

## Known Issues and Limitations

### Security Concerns ⚠️
1. **Plaintext Passwords:** User passwords stored without hashing
   - **Impact:** High security risk
   - **Priority:** Critical
   - **Planned Fix:** Implement `password_hash()` in next iteration

2. **No CSRF Protection:** Forms vulnerable to Cross-Site Request Forgery
   - **Impact:** Medium security risk
   - **Priority:** High
   - **Planned Fix:** Add CSRF token validation

3. **Limited Input Sanitization:** User input not fully sanitized
   - **Impact:** Potential XSS vulnerabilities
   - **Priority:** High
   - **Planned Fix:** Enhanced validation layer

### Functional Limitations
1. **No User Registration:** Users must be added directly to database
   - **Workaround:** Admin creates accounts manually
   - **Planned:** Registration form with email verification

2. **No Search Feature:** Cannot search posts by content
   - **Workaround:** Use tag filtering
   - **Planned:** Full-text search implementation

3. **Page Reloads:** All interactions trigger full page reload
   - **Impact:** Slower user experience
   - **Planned:** AJAX implementation in Phase 2

4. **No Email Notifications:** Users not notified of comments/likes
   - **Planned:** Optional email notification system

### UI/UX Issues
1. **Inconsistent Styling:** Some pages need visual polish
   - **Status:** CSS redesign in progress (see CSS_REDESIGN_MOCKUP.md)

2. **Mobile Experience:** Some elements could be more touch-friendly
   - **Planned:** Enhanced mobile optimization

3. **No Loading Indicators:** Users unsure if actions are processing
   - **Planned:** Loading spinners and feedback messages

### Performance Considerations
1. **No Image Optimization:** Large images not resized/compressed
   - **Impact:** Slower page loads
   - **Planned:** Server-side image processing

2. **No Pagination:** All posts loaded on archive page
   - **Impact:** Performance degrades with many posts
   - **Planned:** Implement pagination or infinite scroll

3. **No Caching:** Database queries not cached
   - **Impact:** Unnecessary database load
   - **Planned:** Consider implementing caching layer

---

## Contributing

This is an academic project, but feedback and suggestions are welcome!

### Development Workflow
1. Create feature branch from `main`
2. Implement changes following coding standards
3. Test thoroughly in local XAMPP environment
4. Commit with descriptive messages
5. Submit for review

### Code Review Checklist
- [ ] Code follows existing style and conventions
- [ ] No security vulnerabilities introduced
- [ ] Database queries use prepared statements
- [ ] Changes don't break existing functionality
- [ ] Comments explain complex logic
- [ ] Responsive design maintained
- [ ] Browser compatibility verified

### Team Responsibilities

**Backend Developer (PHP/MySQL):**
- Database schema modifications
- PHP controllers and processing scripts
- DatabaseHelper methods
- Authentication and session management
- HTML structure in templates

**Frontend Developer (CSS/JavaScript):**
- User interface styling
- Responsive design
- Client-side interactivity
- Form validation
- AJAX implementations
- User experience enhancements

**Collaboration Points:**
- Coordinate on HTML class names and structure
- Agree on data attributes for JavaScript interaction
- Align on form submission and validation flow
- Review UI/UX changes together

---

## Academic Context

**Course:** Web Technologies / Web Development
**Institution:** University of Bologna - Cesena Campus
**Academic Year:** 2023-2024
**Project Type:** Full-Stack Web Application

### Learning Objectives Demonstrated

#### Technical Skills
1. **Server-Side Programming**
   - PHP session management
   - Database interaction with prepared statements
   - File upload handling
   - Template-based rendering

2. **Database Design**
   - Relational schema design
   - Normalization (3NF)
   - Foreign key relationships
   - Junction tables for many-to-many

3. **Web Architecture**
   - MVC pattern adaptation
   - Separation of concerns
   - RESTful URL design
   - Post-Redirect-Get pattern

4. **Security**
   - SQL injection prevention
   - Session security
   - Input validation
   - Access control

5. **Frontend Development**
   - Semantic HTML5
   - Responsive CSS3 design
   - Modern JavaScript (ES6+)
   - Progressive enhancement

6. **Software Engineering**
   - Version control with Git
   - Code organization
   - Documentation
   - Iterative development

#### Soft Skills
- Project planning and roadmapping
- Technical documentation writing
- Code review and collaboration
- Problem-solving and debugging
- Time management and prioritization

### Technologies Covered
✅ HTML5 semantic markup
✅ CSS3 (flexbox, grid, transitions, media queries)
✅ JavaScript ES6+ (DOM, events, fetch API)
✅ PHP 7.4+ (OOP, sessions, file I/O)
✅ MySQL (DDL, DML, prepared statements)
✅ Apache web server
✅ Git version control
✅ XAMPP development environment

---

## License

This project is developed for academic purposes at the University of Bologna.
All rights reserved by the project contributors.

---

## Contributors

- **Giovanni Perreon** - Frontend Development (CSS/JavaScript)
- **[Team Member Name]** - Backend Development (PHP/MySQL)

---

## Acknowledgments

- University of Bologna - Cesena Campus
- Web Technologies course instructors and teaching assistants
- Open source community for documentation and best practices
- Stack Overflow community for troubleshooting assistance

---

## Project Status

**Current Version:** 1.0.0-beta
**Last Updated:** January 2024
**Development Status:** 🚧 Active Development

**Recent Changes:**
- Added anonymous posting feature
- Implemented admin badges
- Set up JavaScript module structure
- Fixed CSS syntax errors
- Created comprehensive documentation

**Next Milestones:**
- Complete AJAX implementation for likes and comments
- Finish CSS forum-style redesign
- Implement password hashing
- Add CSRF protection
- Build user profile pages

---

**Note:** This README is a living document and will be updated as the project evolves. For the most current information, refer to the repository's commit history and documentation files.
