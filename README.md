# API Documentation

## Authentication API

Aplikasi ini menggunakan Laravel Sanctum untuk autentikasi berbasis token. API ini mendukung registrasi pengguna, login, dan verifikasi token.

### Base URL
- Local: `http://localhost:8000/api`

---

### 1. Register a New User

**Endpoint:** `POST /register`

**Description:** Mendaftarkan pengguna baru dan menghasilkan token akses pribadi.

**Request Body:**
```json
{
    "name": "string (required)",
    "email": "string (required, email format)",
    "password": "string (required)",
    "password_confirmation": "string (required, must match password)"
}
```

**Response:**

- **200 OK**
  ```json
  {
      "token": "your_personal_access_token"
  }
  ```

- **422 Unprocessable Entity**
  ```json
  {
      "errors": {
          "name": ["The name field is required."],
          "email": ["The email field is required."],
          "password": ["The password must be at least 2 characters."],
          ...
      }
  }
  ```

---

### 2. Login

**Endpoint:** `POST /login`

**Description:** Mengautentikasi pengguna berdasarkan email dan password, dan menghasilkan token akses pribadi.

**Request Body:**
```json
{
    "email": "string (required, email format)",
    "password": "string (required)"
}
```

**Response:**

- **200 OK**
  ```json
  {
      "token": "your_personal_access_token",
      "name": "User Name"
  }
  ```

- **401 Unauthorized**
  ```json
  {
      "message": "Unauthorized"
  }
  ```

---

### 3. Verify Token

**Endpoint:** `GET /verify-token`

**Description:** Memverifikasi apakah token akses yang diberikan valid.

**Headers:**
- Authorization: `Bearer {your_personal_access_token}`

**Response:**

- **200 OK**
  ```json
  {
      "message": "Token is valid",
      "user": {
          "id": 1,
          "name": "User Name",
          "email": "user@example.com"
      }
  }
  ```

- **401 Unauthorized**
  ```json
  {
      "message": "Token not provided"
  }
  ```

  atau

  ```json
  {
      "message": "Invalid token"
  }
  ```

---

### 4. Get Authenticated User Details

**Endpoint:** `GET /user`

**Description:** Mengambil detail pengguna yang sedang login berdasarkan token akses yang diberikan.

**Headers:**
- Authorization: `Bearer {your_personal_access_token}`

**Response:**

- **200 OK**
  ```json
  {
      "id": 1,
      "name": "User Name",
      "email": "user@example.com"
  }
  ```

- **401 Unauthorized**
  ```json
  {
      "message": "Unauthenticated."
  }
  ```

---

## Authentication

Akses ke endpoint `/verify-token` dan `/user` memerlukan autentikasi dengan token dari Laravel Sanctum. Pastikan untuk menyertakan header berikut pada setiap request ke endpoint yang memerlukan autentikasi:

```http
Authorization: Bearer {your_personal_access_token}
```

---

## Response Codes

- **200** - Request was successful.
- **201** - Resource was created successfully.
- **401** - Unauthorized access or invalid credentials.
- **422** - Validation error.
- **500** - Internal server error.
```

Dokumentasi ini menjelaskan bagaimana menggunakan API untuk autentikasi pengguna dan mengelola token. Sesuaikan base URL jika diperlukan, tergantung pada lingkungan pengembangan atau produksi.
