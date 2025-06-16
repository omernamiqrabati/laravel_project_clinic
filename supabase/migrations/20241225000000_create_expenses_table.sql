-- Create expenses table for dental clinic
CREATE TABLE expenses (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    
    -- Basic expense information
    expense_date DATE NOT NULL,
    expense_month INTEGER NOT NULL CHECK (expense_month >= 1 AND expense_month <= 12),
    expense_year INTEGER NOT NULL CHECK (expense_year >= 2020),
    
    -- Expense details
    description TEXT NOT NULL,
    category VARCHAR(100) NOT NULL,
    subcategory VARCHAR(100),
    amount DECIMAL(12,2) NOT NULL CHECK (amount >= 0),
    currency VARCHAR(3) DEFAULT 'USD',
    
    -- Dental clinic specific categories
    expense_type VARCHAR(50) NOT NULL CHECK (expense_type IN (
        'Equipment',
        'Supplies',
        'Utilities',
        'Staff',
        'Rent',
        'Insurance',
        'Marketing',
        'Training',
        'Maintenance',
        'Laboratory',
        'Other'
    )),
    
    -- Payment information
    payment_method VARCHAR(50) DEFAULT 'Cash' CHECK (payment_method IN (
        'Cash',
        'Credit Card',
        'Bank Transfer',
        'Check',
        'Online Payment'
    )),
    
    -- Additional details
    vendor_name VARCHAR(200),
    receipt_number VARCHAR(100),
    notes TEXT,
    
    -- Status tracking
    status VARCHAR(20) DEFAULT 'Active' CHECK (status IN ('Active', 'Cancelled', 'Refunded')),
    
    -- Metadata
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id)
);

-- Create indexes for better performance
CREATE INDEX idx_expenses_date ON expenses(expense_date);
CREATE INDEX idx_expenses_month_year ON expenses(expense_month, expense_year);
CREATE INDEX idx_expenses_category ON expenses(category);
CREATE INDEX idx_expenses_type ON expenses(expense_type);
CREATE INDEX idx_expenses_vendor ON expenses(vendor_name);

-- Create trigger for updating updated_at
CREATE TRIGGER update_expenses_updated_at
    BEFORE UPDATE ON expenses
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column();

-- Create view for monthly expense summary
CREATE VIEW monthly_expense_summary AS
SELECT 
    expense_year,
    expense_month,
    expense_type,
    category,
    COUNT(*) as transaction_count,
    SUM(amount) as total_amount,
    AVG(amount) as average_amount
FROM expenses 
WHERE status = 'Active'
GROUP BY expense_year, expense_month, expense_type, category
ORDER BY expense_year DESC, expense_month DESC;

-- Sample data for testing
INSERT INTO expenses (
    expense_date, expense_month, expense_year, description, category, 
    expense_type, amount, vendor_name, payment_method
) VALUES 
    ('2024-12-01', 12, 2024, 'Dental X-ray Film Rolls', 'Medical Supplies', 'Supplies', 245.50, 'DentalSupplyCo', 'Credit Card'),
    ('2024-12-02', 12, 2024, 'Office Rent', 'Facility Costs', 'Rent', 3500.00, 'Property Management LLC', 'Bank Transfer'),
    ('2024-12-03', 12, 2024, 'Dental Chair Maintenance', 'Equipment Service', 'Maintenance', 450.00, 'EquipCare Services', 'Check'),
    ('2024-12-05', 12, 2024, 'Electricity Bill', 'Utilities', 'Utilities', 320.75, 'Electric Company', 'Online Payment'),
    ('2024-12-07', 12, 2024, 'Staff Training Course', 'Professional Development', 'Training', 800.00, 'Dental Education Institute', 'Credit Card'); 