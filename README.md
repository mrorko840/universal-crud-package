# 🧩 Universal CRUD Package for Laravel
A Fully Dynamic, Table-Agnostic CRUD & File Upload API for Any Laravel Project

![Laravel](https://img.shields.io/badge/Laravel-10%2F11-red?style=flat-square&logo=laravel)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)
![PHP](https://img.shields.io/badge/PHP-%3E%3D%208.1-blue?style=flat-square&logo=php)

Universal CRUD Package একটি powerful Laravel package—যা install করার মুহূর্তেই আপনার project-এর যেকোনো database table-এ dynamic CRUD API generate করে দেয়।  
Table discovery, column introspection, image upload, file storage—সবকিছু config-driven ভাবে control করতে পারবেন।

---

## 🚀 Features

- 🔍 Auto Table Discovery  
- 🧱 Dynamic Columns Detection  
- 📝 Full CRUD API  
- 🖼️ Image / File Upload Support  
- 🛡️ Secure & Configurable  
- ⚡ Zero Model / Migration Required  
- 🔌 Laravel Auto-Discovery Supported  

---

## 📦 Installation

### 1. Composer Require

```bash
composer require mrorko840/universal-crud-package
```

### Private Repo Setup

```json
"repositories": [
  {
    "type": "vcs",
    "url": "git@github.com:mrorko840/universal-crud-package.git"
  }
]
```

Then:

```bash
composer require mrorko840/universal-crud-package:"dev-main"
```

---

## ⚙️ Publish Config

```bash
php artisan vendor:publish --tag=universal-crud-config
```

---

## 🔧 Configuration

```php
return [
    'base_uri' => 'universal-crud',
    'auth_middleware' => ['api'],
    'allowed_tables' => ['*'],
    'hidden_columns' => [],
    'upload_disk' => 'public',
    'upload_base_path' => 'uploads',
];
```

---

## 🛠️ API Endpoints

### Tables List
```
GET /api/universal-crud/tables
```

### Table Columns
```
GET /api/universal-crud/tables/{table}/columns
```

### List Records
```
GET /api/universal-crud/{table}
```

### Show Record
```
GET /api/universal-crud/{table}/{id}
```

### Create (JSON)
```
POST /api/universal-crud/{table}
```

### Create With File Upload
Multipart form-data:

- name  
- price  
- image  
- _upload_path (optional)  

### Update
```
POST /api/universal-crud/{table}/{id}
```

### Delete
```
DELETE /api/universal-crud/{table}/{id}
```

---

## 🖼️ File Upload

Stored under:

```
storage/app/public/uploads/... (default)
```

---

## 📂 Folder Structure

```
universal-crud-package/
  ├── src/
  ├── config/
  ├── routes/
  ├── composer.json
  └── README.md
```

---

## 🪪 License
MIT License

---

## ⭐ Support
যদি package টি আপনার কাজে লাগে, GitHub এ একটি ⭐ Star দিন!
