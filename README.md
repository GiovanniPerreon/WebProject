# Spotted Unibo Cesena

A forum-style web platform for University of Bologna (Cesena Campus) students to share posts, discussions, and campus-related content.

## Features

- Post creation with tags, images, and anonymous posting
- Comment system and like functionality
- Tag-based filtering and content organization
- User authentication with admin roles
- Dark theme responsive design

## Technology Used

- **Frontend:** HTML5, CSS3, JavaScript (ES6+)
- **Backend:** PHP, MySQL
- **Server:** Apache (XAMPP)

## Installation

1. **Clone repository:**
   ```bash
   git clone https://github.com/GiovanniPerreon/WebProject.git
   ```

2. **Start XAMPP:** Launch Apache and MySQL

3. **Create database:** Import SQL files via phpMyAdmin:
   - `db/creazione_db.sql` (schema)
   - `db/inserisci_dati.sql` (sample data)

4. **Access application:** `http://localhost/WebProject/`

## Project Structure

```
WebProject/
├── css/              # Stylesheets
├── js/               # JavaScript modules
├── template/         # PHP view templates
├── db/               # Database files
├── upload/           # User uploads
├── utils/            # Helper functions
└── *.php             # Controllers and processors
```
