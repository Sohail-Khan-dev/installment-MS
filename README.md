# Installment Management System

A comprehensive web-based Installment Management System built with Laravel (PHP) for backend and HTML, CSS, JavaScript, and Bootstrap for frontend. This system helps businesses manage installment payments for products and services efficiently.

## 📋 Features

### ✅ Completed Features

#### 1. **User Authentication & Authorization**
   - Secure admin login system using Laravel Breeze
   - Password-protected access to all system features
   - Session management and user profile management

#### 2. **Product/Service Catalog Management**
   - Add, edit, delete, and view products
   - Track product inventory (stock quantity)
   - Product categorization
   - SKU management
   - Price management

#### 3. **Customer Management**
   - Complete customer registration with personal details
   - Customer credit limit management
   - CNIC (National ID) tracking
   - Customer status tracking (Active/Inactive/Blocked)
   - Address and contact information management

#### 4. **Installment Plan Creation & Management**
   - Create flexible installment plans for customers
   - Support for different payment frequencies (Monthly, Weekly, Bi-weekly, Quarterly)
   - Interest rate calculation
   - Down payment management
   - Automatic installment amount calculation
   - Plan status tracking (Active, Completed, Cancelled, Defaulted)

#### 5. **Payment Tracking & Recording**
   - Record customer payments against installment plans
   - Multiple payment methods support (Cash, Bank Transfer, Card, Cheque, Online)
   - Payment status tracking (Pending, Paid, Late, Failed, Refunded)
   - Late fee calculation
   - Payment reference number tracking

#### 6. **Invoice Generation**
   - Automatic invoice generation for installments
   - Invoice status tracking (Draft, Sent, Paid, Overdue, Cancelled)
   - Tax calculation support

#### 7. **Dashboard & Analytics**
   - Real-time statistics display
   - Total customers and active customers count
   - Active and completed installment plans tracking
   - Revenue tracking
   - Pending payments overview
   - Recent activities monitoring
   - Overdue payments alerts

#### 8. **Reporting Features**
   - View overdue payments
   - Track payment history
   - Monitor installment plan progress
   - Customer payment history

## 🔗 System URLs and Endpoints

### Authentication Routes
- `GET /` - Redirects to login
- `GET /login` - Login page
- `POST /login` - Process login
- `GET /register` - Registration page (if enabled)
- `POST /logout` - Logout

### Dashboard
- `GET /dashboard` - Main dashboard with statistics and overview

### Products Management
- `GET /products` - List all products
- `GET /products/create` - Create new product form
- `POST /products` - Store new product
- `GET /products/{id}` - View product details
- `GET /products/{id}/edit` - Edit product form
- `PUT /products/{id}` - Update product
- `DELETE /products/{id}` - Delete product

### Customer Management
- `GET /customers` - List all customers
- `GET /customers/create` - Create new customer form
- `POST /customers` - Store new customer
- `GET /customers/{id}` - View customer details
- `GET /customers/{id}/edit` - Edit customer form
- `PUT /customers/{id}` - Update customer
- `DELETE /customers/{id}` - Delete customer

### Installment Plans
- `GET /installment-plans` - List all installment plans
- `GET /installment-plans/create` - Create new plan form
- `POST /installment-plans` - Store new plan
- `GET /installment-plans/{id}` - View plan details
- `POST /installment-plans/{id}/payment` - Record payment for plan

### Payments
- `GET /payments` - List all payments
- `GET /payments?status=pending` - Filter pending payments
- `GET /payments?status=paid` - Filter paid payments
- `GET /payments?overdue=1` - Filter overdue payments
- `GET /payments/{id}` - View payment details
- `POST /payments/{id}/mark-paid` - Mark payment as paid

## 💾 Database Structure

### Tables
1. **users** - System administrators
2. **products** - Product catalog
3. **customers** - Customer information
4. **installment_plans** - Installment plan details
5. **payments** - Payment records
6. **invoices** - Invoice records

### Key Relationships
- Customer → has many → Installment Plans
- Product → has many → Installment Plans
- Installment Plan → has many → Payments
- Installment Plan → has many → Invoices
- Customer → has many → Payments
- Customer → has many → Invoices

## 🚀 Installation Guide

### Prerequisites
- PHP >= 8.1
- Composer
- MySQL/MariaDB
- Node.js & NPM

### Step 1: Clone or Download the Project
```bash
# If you have the project in a zip file
unzip installment-management-system.zip
cd installment-management-system

# OR if cloning from repository
git clone [repository-url]
cd installment-management-system
```

### Step 2: Install PHP Dependencies
```bash
composer install
```

### Step 3: Install JavaScript Dependencies
```bash
npm install
```

### Step 4: Environment Configuration
```bash
# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 5: Configure Database
Edit the `.env` file and update database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=installment_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 6: Create Database
```sql
# Login to MySQL
mysql -u root -p

# Create database
CREATE DATABASE installment_db;
EXIT;
```

### Step 7: Run Migrations
```bash
php artisan migrate
```

### Step 8: Seed Sample Data (Optional)
```bash
php artisan db:seed
```
This will create:
- Admin user: `admin@example.com` / `password`
- Sample products, customers, and installment plans

### Step 9: Build Assets
```bash
npm run build
```

### Step 10: Start the Development Server
```bash
php artisan serve
```

The application will be available at: `http://localhost:8000`

## 🔑 Default Login Credentials

- **Email**: admin@example.com
- **Password**: password

## 📱 Usage Guide

### Creating an Installment Plan
1. Login to the system
2. Navigate to "Customers" and create/select a customer
3. Navigate to "Products" and ensure products are available
4. Go to "Installment Plans" → "Create New Plan"
5. Select customer and product
6. Enter down payment amount
7. Set number of installments and payment frequency
8. Set interest rate (if applicable)
9. Choose start date
10. Submit to create the plan

### Recording a Payment
1. Navigate to "Payments"
2. Find the pending payment
3. Click "Mark Paid" or go to the installment plan
4. Enter payment details and reference number
5. Submit to record the payment

### Managing Overdue Payments
1. Go to Dashboard to see overdue payments overview
2. Navigate to "Payments" → "Overdue" filter
3. Contact customers with overdue payments
4. Record payments when received

## 🛠️ Technical Stack

- **Backend**: Laravel 12.x (PHP Framework)
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Database**: MySQL/MariaDB
- **Authentication**: Laravel Breeze
- **Package Manager**: Composer (PHP), NPM (JavaScript)

## 📈 Future Enhancements

- SMS/Email notifications for payment reminders
- Customer portal for self-service
- Mobile application support
- Advanced reporting with charts and graphs
- Multi-language support
- Backup and restore functionality
- Integration with payment gateways
- Barcode/QR code generation for invoices
- Automated late fee calculation
- Credit score tracking

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Verify database credentials in `.env` file
   - Ensure MySQL/MariaDB service is running
   - Check if database exists

2. **Permission Errors**
   ```bash
   chmod -R 777 storage
   chmod -R 777 bootstrap/cache
   ```

3. **Blank Page After Installation**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

4. **npm run build fails**
   ```bash
   rm -rf node_modules
   npm install
   npm run build
   ```

## 📝 License

This project was developed as a semester project for BS Computer Science at Government Postgraduate College Landikotal, Pakistan (Session 2021-2025).

## 👥 Contributors

- **Muhammad Yaseen** - Roll No: 210314
- **Muhammad Zeeshan** - Roll No: 210316
- **Supervisor**: Prof. Muhammad Junaid

## 📞 Support

For issues or questions about this system, please contact the development team or refer to the documentation.

---

**Note**: This is an educational project developed for academic purposes. Make sure to enhance security measures before deploying in a production environment.