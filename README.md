# MinBlog

Welcome to **MinBlog**! This is a minimal blogging framework. Simple, fast, and easy to use.

## Description

MinBlog is designed to create a simple web application. It is not designed to be highly secure, so use it at your own risk. If you encounter any issues, please report them to help improve the framework.

## Folder Structure

MinBlog's folder structure is organized as follows:

```
MinBlog/
├── bin/
│   ├── Config.php
│   ├── Controller.php
│   ├── Database.php
│   ├── MinBlog.php
│   ├── MinContainer.php
│   ├── Router.php
│   └── ViewHandler.php
├── configs/
│   └── Dsn.php
├── controllers/
│   └── HomeController.php
├── models/
│   └── WebSession.php
├── public/
│   ├── lib/
│   │   ├── jquery.min.js
│   │   ├── site.css
│   │   └── site.js
│   ├── resources/
│   ├── .htaccess
│   ├── web.config
│   └── index.php
├── sql/
│   ├── tables/
│   ├── views/
│   ├── procedures/
│   ├── functions/
│   ├── seeders/
│   └── migrations.txt
├── templates/
│   └── EmptyTemplate.php
├── views/
│   ├── home/
│   │   └── index.php
├── .env.example
├── .gitignore
├── .htaccess
├── autoload.php
├── KeyGen.php
├── migration.php
├── LICENSE
└── README.md
```

### Directory Details

- **bin/**: Contains core framework files.
- **configs/**: Configuration files.
- **controllers/**: Controller files to handle web requests.
- **models/**: Data models.
- **public/**: Publicly accessible files including JavaScript, CSS, and entry points.
- **sql/**: SQL scripts for database operations.
- **templates/**: Template files for the views.
- **views/**: View files, organized by modules.

## Setting Up

1. **Clone the repository**:

   ```sh
   git clone https://github.com/bambangy/minblog.git
   cd minblog
   ```

2. **Configure the environment**:

   - Copy `.env.example` to `.env` and adjust the settings as needed.

3. **Generate Key for security encrypt and decrypt data**

   - Run command these command to generate random key

   ```
   php KeyGen.php
   ```

   - Copy and paste the key into the `.env` file

4. **Run the application**:
   - Ensure your web server points to the `public` directory.
   - Access the application in your web browser.

## Running SQL Scripts

The `migration.php` script helps run SQL files to set up the database.

### Usage

Ensure you update the `.env` with your database connection details and execute the script from the command line:

```
php migration.php
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

Feel free to explore and contribute to this project! For any issues or feature requests, please create an issue on the GitHub repository.
