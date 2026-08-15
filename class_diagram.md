# Class Diagram / Class Relation Diagram (CRD) - Semarang Fencing Club (SFC)

Berikut adalah Class Diagram yang menggambarkan hubungan antar-kelas (Controller, Model, dan Middleware) dalam arsitektur aplikasi Laravel SFC.

## 📊 Class Diagram (Mermaid UML)

```mermaid
classDiagram
    class Controller {
        <<Base>>
    }

    class AuthController {
        +showLogin() View
        +processLogin(Request) Redirect
        +showRegister() View
        +processRegister(Request) Redirect
        +logout(Request) Redirect
    }

    class MemberController {
        +index() View
        +absenPage() View
        +storeAbsen(Request) Redirect
        +storeLeave(Request) Redirect
        +updateAvatar(Request) JsonResponse
    }

    class PelatihController {
        +index() View
        +approveLeave(id) Redirect
        +rejectLeave(id) Redirect
        +storeGallery(Request) Redirect
        +storeNews(Request) Redirect
        +storeAchievement(Request) Redirect
    }

    class User {
        +bigint id
        +string name
        +string nia
        +string email
        +string password
        +string role
        +string avatar_url
        +casts() array
        +attendances() HasMany
        +leaveRequests() HasMany
    }

    class TrainingLocation {
        +bigint id
        +string name
        +decimal latitude
        +decimal longitude
        +integer radius_meter
        +attendances() HasMany
    }

    class Attendance {
        +bigint id
        +bigint user_id
        +bigint training_location_id
        +string photo_path
        +decimal latitude
        +decimal longitude
        +decimal distance_meter
        +string status
        +timestamp attendance_date
        +user() BelongsTo
        +trainingLocation() BelongsTo
    }

    class LeaveRequest {
        +bigint id
        +bigint user_id
        +string type
        +text reason
        +string proof_path
        +date date
        +string status
        +user() BelongsTo
    }

    class Achievement {
        +bigint id
        +string title
        +string winner_name
        +string category
        +integer year
        +string location
        +text description
        +text image_path
    }

    class News {
        +bigint id
        +string title
        +text content
        +date date
        +string icon
        +text image_path
    }

    class Gallery {
        +bigint id
        +string image_path
        +string caption
    }

    class RoleMiddleware {
        +handle(Request, Closure, role) Response
    }

    %% Relationships
    Controller <|-- AuthController
    Controller <|-- MemberController
    Controller <|-- PelatihController

    MemberController ..> User : "uses"
    MemberController ..> Attendance : "creates/reads"
    MemberController ..> LeaveRequest : "creates/reads"
    MemberController ..> TrainingLocation : "reads"

    PelatihController ..> User : "reads"
    PelatihController ..> LeaveRequest : "updates (approve/reject)"
    PelatihController ..> Achievement : "creates"
    PelatihController ..> News : "creates"
    PelatihController ..> Gallery : "creates"

    AuthController ..> User : "authenticates/creates"

    User "1" *-- "0..*" Attendance : "has"
    User "1" *-- "0..*" LeaveRequest : "has"
    TrainingLocation "1" *-- "0..*" Attendance : "has"
    
    RoleMiddleware ..> User : "authorizes by role"
```

---

## 📝 Penjelasan Arsitektur Kelas

Aplikasi ini menggunakan pola arsitektur **MVC (Model-View-Controller)** bawaan Laravel:

### 1. Controllers (Logika Bisnis)
*   **`AuthController`**: Mengatur proses otentikasi pengguna, mulai dari menampilkan form login/registrasi, memvalidasi kredensial pengguna (menggunakan kombinasi NIA & password), hingga menangani proses registrasi anggota baru dan pembuatan nomor NIA secara otomatis.
*   **`MemberController`**: Mengatur interaksi khusus anggota (*member*), seperti menampilkan halaman dashboard member, memproses absensi lokasi latihan (menggunakan GPS & kamera/upload foto), memproses pengajuan izin latihan, serta memproses unggah/potong foto profil (*avatar*).
*   **`PelatihController`**: Mengatur halaman dashboard pelatih/admin. Memiliki fungsi untuk menyetujui atau menolak izin latihan anggota, serta menambahkan konten dinamis seperti berita kejuaraan (`News`), data prestasi medali (`Achievement`), dan foto-foto dokumentasi (`Gallery`).

### 2. Models (Struktur Data & Relasi ORM)
*   **`User`**: Representasi entitas pengguna di sistem. Model ini terhubung ke banyak data presensi (`attendances`) dan perizinan (`leaveRequests`).
*   **`TrainingLocation`**: Representasi titik koordinat lokasi latihan. Terhubung dengan data absensi untuk menentukan apakah absensi dilakukan di lokasi ini.
*   **`Attendance`**: Representasi data absensi latihan anggota. Memiliki relasi timbal balik (`BelongsTo`) ke model `User` dan `TrainingLocation`.
*   **`LeaveRequest`**: Representasi pengajuan izin tidak hadir latihan yang diajukan oleh anggota. Terhubung balik ke model `User`.
*   **`Achievement`**, **`News`**, **`Gallery`**: Model-model mandiri yang menyimpan konten dinamis informasi klub Fencing.

### 3. Middleware (Keamanan & Filter)
*   **`RoleMiddleware`**: Digunakan untuk membatasi akses rute (*routes*) tertentu berdasarkan peran pengguna. Contohnya, memastikan hanya pengguna dengan role `pelatih` yang bisa mengakses modul pengelolaan data berita, galeri, dan verifikasi izin.
