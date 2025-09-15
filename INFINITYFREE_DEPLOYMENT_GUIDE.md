# 🚀 InfinityFree Deployment Guide for Installment Management System

## 📝 Step-by-Step Deployment Instructions

### **Step 1: Create InfinityFree Account**

1. **Visit**: https://infinityfree.net
2. **Sign Up** for a free account
3. **Verify** your email address
4. **Login** to your account

### **Step 2: Create a New Hosting Account**

1. Click **"Create Account"** from dashboard
2. Choose a **subdomain** or use custom domain:
   - Example: `installment-ms.infinityfreeapp.com`
3. Set a **password** for the account
4. Click **"Create Account"**
5. Wait for account activation (usually instant)

### **Step 3: Get Your Hosting Details**

After account creation, note down:
- **FTP Hostname**: ftpupload.net
- **FTP Username**: if0_XXXXXXXX (your account username)
- **FTP Password**: (the password you set)
- **MySQL Hostname**: sqlXXX.infinityfree.com
- **MySQL Database**: if0_XXXXXXXX_installment
- **MySQL Username**: if0_XXXXXXXX
- **MySQL Password**: (from control panel)
- **Website URL**: http://yoursite.infinityfreeapp.com

### **Step 4: Setup Database**

1. In InfinityFree Control Panel, click **"MySQL Databases"**
2. Create a new database named: `installment`
3. Note the full database name: `if0_XXXXXXXX_installment`
4. Click **"phpMyAdmin"** to open database manager
5. Select your database
6. Click **"Import"** tab
7. Upload the `database_export.sql` file from the project
8. Click **"Go"** to import

### **Step 5: Configure Environment File**

1. Open `.env.production` file in the project
2. Update with your InfinityFree credentials:

```env
APP_NAME="Installment Management System"
APP_ENV=production
APP_KEY=base64:IvajL5L9ZzPkx0LPP8+vRdQpv9cWZSGNJ/+YvXE7ogg=
APP_DEBUG=false
APP_URL=http://yoursite.infinityfreeapp.com

DB_CONNECTION=mysql
DB_HOST=sqlXXX.infinityfree.com
DB_PORT=3306
DB_DATABASE=if0_XXXXXXXX_installment
DB_USERNAME=if0_XXXXXXXX
DB_PASSWORD=YourMySQLPasswordHere
```

3. Rename `.env.production` to `.env`

### **Step 6: Prepare Files for Upload**

Since InfinityFree has no Composer, you need to upload the vendor folder:

1. **On your local machine**, run:
```bash
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

2. **Generate new APP_KEY** (if needed):
```bash
php artisan key:generate --show
```
Copy this key to your `.env` file

### **Step 7: Upload Files via FTP**

#### **Using FileZilla (Recommended)**

1. **Download FileZilla**: https://filezilla-project.org
2. **Open FileZilla** and enter:
   - **Host**: ftpupload.net
   - **Username**: if0_XXXXXXXX
   - **Password**: Your FTP password
   - **Port**: 21
3. Click **"Quickconnect"**

#### **Upload Process**

1. Navigate to `htdocs` folder on the server (right panel)
2. Delete the default `index.html` if present
3. Upload ALL project files and folders:

**IMPORTANT Upload Order** (to avoid timeout):
1. First upload these files individually:
   - `index.php` (root file we created)
   - `.env` (the configured one)
   - `.htaccess`
   
2. Then upload folders one by one:
   - `app/` folder
   - `bootstrap/` folder
   - `config/` folder
   - `database/` folder
   - `public/` folder
   - `resources/` folder
   - `routes/` folder
   - `storage/` folder (set permissions to 777)
   
3. Finally, upload the large folder:
   - `vendor/` folder (this will take 20-30 minutes)

**Note**: The vendor folder is large (~50MB) and contains many files. Be patient!

### **Step 8: Set Permissions**

Using FTP client, right-click and set permissions (CHMOD):
- `storage/` folder and all subfolders: **777**
- `bootstrap/cache/` folder: **777**
- `.env` file: **644**

### **Step 9: Final Configuration**

1. Visit your website: `http://yoursite.infinityfreeapp.com`
2. You should see the login page
3. Login with:
   - Email: `admin@example.com`
   - Password: `password`

### **Step 10: Troubleshooting**

#### **If you see "500 Internal Server Error":**
1. Check `.htaccess` file is properly uploaded
2. Verify `.env` file has correct database credentials
3. Ensure `vendor/` folder is completely uploaded

#### **If you see "Database Connection Error":**
1. Verify database credentials in `.env`
2. Check if database is imported correctly
3. Ensure MySQL hostname is correct (sqlXXX.infinityfree.com)

#### **If styles are missing:**
1. Run `npm run build` locally before uploading
2. Ensure `public/build/` folder is uploaded
3. Check APP_URL in `.env` matches your domain

#### **If you can't login:**
1. Import the database SQL again
2. Check users table has the admin user
3. Try resetting password in database

## 📂 **File Structure on InfinityFree**

```
htdocs/
├── index.php              (InfinityFree entry point)
├── .env                   (Configuration file)
├── .htaccess             (Apache configuration)
├── app/                  (Application logic)
├── bootstrap/            (Laravel bootstrap)
├── config/               (Configuration files)
├── database/             (Migrations and seeders)
├── public/               (Public assets)
│   ├── index.php        (Laravel entry point)
│   ├── .htaccess        (Public htaccess)
│   └── build/           (Compiled CSS/JS)
├── resources/            (Views and raw assets)
├── routes/               (Application routes)
├── storage/              (Logs, cache - chmod 777)
└── vendor/               (Dependencies - large folder)
```

## 🔐 **Security Recommendations**

1. **Change default admin password** immediately after deployment
2. **Update APP_KEY** in production
3. **Set APP_DEBUG=false** in production
4. **Use HTTPS** if you have a custom domain (InfinityFree supports free SSL)
5. **Regularly backup** your database

## 🌐 **Alternative: Using InfinityFree File Manager**

If FTP is too slow, you can:
1. Create a ZIP file of your project (excluding vendor)
2. Upload the ZIP via InfinityFree File Manager
3. Extract it in the file manager
4. Upload vendor folder separately or in chunks

## 📱 **Custom Domain Setup (Optional)**

1. Register a domain (e.g., from Namecheap, GoDaddy)
2. In InfinityFree, go to "Addon Domains"
3. Add your domain
4. Update nameservers at your domain registrar:
   - ns1.infinityfree.net
   - ns2.infinityfree.net
5. Wait 24-48 hours for propagation
6. Update APP_URL in .env

## ✅ **Deployment Checklist**

- [ ] InfinityFree account created
- [ ] Database created and imported
- [ ] .env file configured with correct credentials
- [ ] All files uploaded via FTP
- [ ] Storage permissions set to 777
- [ ] Website accessible and login works
- [ ] Admin password changed
- [ ] Test all features

## 📞 **Support Resources**

- **InfinityFree Forum**: https://forum.infinityfree.net
- **InfinityFree Knowledge Base**: https://infinityfree.net/support
- **Laravel Documentation**: https://laravel.com/docs

## 🎉 **Congratulations!**

Once completed, your Installment Management System will be live and accessible from anywhere in the world, completely free!

**Your Live URL**: `http://yoursite.infinityfreeapp.com`

---

**Note**: InfinityFree is a free service with limitations:
- Daily hits limit: 50,000
- CPU limit: 20%
- File size limit: 10MB
- No email sending (SMTP needed)
- Occasional downtime for free accounts

For production use with real customers, consider upgrading to paid hosting.