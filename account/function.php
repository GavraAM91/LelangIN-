<?php

class IDGenerator
{
    private $lastID;

    public function __construct($lastID = 0)
    {
        $this->lastID = $lastID;
    }

    public function generateID()
    {
        $this->lastID++;
        return "USR" . str_pad($this->lastID, 3, "0", STR_PAD_LEFT);
    }
}
class database
{
    private $host = "localhost"; // Host database
    private $db_name = "db_lelang"; // Nama database
    private $username = "root"; // Username database
    private $password = ""; // Password database
    private $conn;

    // Method untuk membuat koneksi ke database
    public function getConnection()
    {
        $this->conn = null; // Inisialisasi koneksi sebagai null

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            // Set charset koneksi ke utf8
            $this->conn->set_charset("utf8");
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        return $this->conn; // Mengembalikan objek koneksi
    }

    public function getLastUserID()
    {
        $this->conn = $this->getConnection();
        $result = $this->conn->query("SELECT id_user FROM tb_account ORDER BY id_user DESC LIMIT 1");
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastID = $row['id_user'];
            return intval(substr($lastID, 3)); // Mengembalikan hanya bagian numerik dari ID
        } else {
            return 0; // Jika tidak ada entri, mulai dari 0
        }
    }
}


class account
{
    private $username, $password, $email, $confirmPassword;

    public function __construct($username = "username", $password = "password", $confirmPassword = "confirmPassword", $email = "email")
    {
        $this->username = $username;
        $this->password = $password;
        $this->confirmPassword = $confirmPassword;
        $this->email = $email;
    }

    //login 
    public function login()
    {
        $db = new database();
        $conn = $db->getConnection(); // Dapatkan koneksi database

        // Siapkan query untuk mencari user berdasarkan username
        $query = $conn->prepare("SELECT * FROM tb_account WHERE username = ?");
        $query->bind_param("s", $this->username);
        $query->execute();
        $result = $query->get_result();

        $password = $this->password;

        // Cek jika user ditemukan
        if ($user = $result->fetch_assoc()) {
            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                // Password benar, login berhasil
                $_SESSION['role'] = $user['role']; // Menyimpan role ke dalam session
    
                // Redireksi berdasarkan role
                if ($user['role'] == 'admin') {
                    echo "<script>
                        alert('LOGIN BERHASIL!');
                    </script>";
                    session_start(); // Memulai sesi 
                    $_SESSION['username'] = $this->username;
                    header('Location: ../admin/index.php');
                    exit(); // Menambahkan exit setelah header untuk menghentikan eksekusi script lebih lanjut
                } else if ($user['role'] == 'user') {
                    echo "<script>
                        alert('LOGIN BERHASIL!');
                    </script>";
                    session_start(); // Memulai sesi 
                    $_SESSION['username'] = $this->username;
                    header('Location: ../index.php');
                    exit(); // Menambahkan exit setelah header
                }
            } else {
                // Password salah
                echo "<script>
                    alert('Password salah!');
                    </script>";
                return false;
            }
        } else {
            // Username tidak ditemukan
            echo "<script>
                alert('Username tidak ditemukan!');
                </script>";
            return false;
        }
    }

    public function signup()
    {

        $db = new database();
        $lastIDFromDB = $db->getLastUserID(); // Dapatkan lastID dari database
    
        $generator = new IDGenerator($lastIDFromDB); // Inisialisasi IDGenerator dengan lastID
        $newID = $generator->generateID(); // Generate ID baru
    
        //check username sudah ada atau belum
        $query = $db->getConnection()->query("SELECT username FROM tb_account WHERE username = '$this->username'");
        if ($query->fetch_assoc()) {
            echo "<script>
            alert('username sudah digunakan');
        </script>";
            return false;
        }

        //check email sudah ada atau belum
        $query = $db->getConnection()->query("SELECT username FROM tb_account WHERE email = '$this->email'");
        if ($query->fetch_assoc()) {
            echo "<script>
            alert('Email sudah digunakan');
        </script>";
            return false;
        }
        // Konfirmasi password
        if ($this->password !== $this->confirmPassword) {
            echo "<script>
        alert('Password tidak sama!');
        </script>";
            return false;
        }


        // Hash password
        $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

        // Menambahkan ke database
        $sql = $db->getConnection()->prepare("INSERT INTO tb_account (id_user, username, password, email) VALUES (?, ?, ?, ?)");
        $sql->bind_param("ssss", $newID, $this->username, $password_hash, $this->email);
        if ($sql->execute()) {
            header('Location: login.php');
            echo "<script>
        alert('Pendaftaran berhasil!');
        </script>";
        
        } else {
            echo "<script>
        alert('Terjadi kesalahan saat mendaftar.');
        </script>";
        }
    }

    
}
