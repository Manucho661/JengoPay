<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


try {

    $stmt = $pdo->query("
                  SELECT
                      i.id,
                      i.invoice_no,
                      -- Get tenant name from tenants table
                      CONCAT(t.first_name, ' ', t.last_name) AS tenant_name,
                      COALESCE(t.phone, i.phone) AS tenant_phone,
                      COALESCE(t.email, i.email) AS tenant_email,
                      i.invoice_date,
                      i.due_date,
                      i.notes AS description,
                      COALESCE(i.subtotal, 0) AS subtotal,
                      COALESCE(i.total, 0) AS total,
                      COALESCE(i.taxes, 0) AS taxes,
                      i.status,
                      i.payment_status,
                      t.account_no AS account_no,
                      
                      -- Payment calculations
                      (SELECT COALESCE(SUM(p.amount), 0)
                      FROM payments p
                      WHERE p.invoice_id = i.id) AS paid_amount,
                      
                      -- Invoice items totals (alternative calculation)
                      (SELECT COALESCE(SUM(unit_price * quantity), 0) 
                      FROM invoice_items 
                      WHERE invoice_id = i.id) AS items_subtotal,
                      
                      (SELECT COALESCE(SUM(tax_amount), 0) 
                      FROM invoice_items 
                      WHERE invoice_id = i.id) AS items_taxes,
                      
                      (SELECT COALESCE(SUM(total_price), 0) 
                      FROM invoice_items 
                      WHERE invoice_id = i.id) AS items_total,
                      
                      -- Final display values (use items if they exist, otherwise use invoice totals)
                      CASE
                          WHEN EXISTS (SELECT 1 FROM invoice_items WHERE invoice_id = i.id)
                          THEN (SELECT COALESCE(SUM(unit_price * quantity), 0) FROM invoice_items WHERE invoice_id = i.id)
                          ELSE i.subtotal
                      END AS display_subtotal,
                      
                      CASE
                          WHEN EXISTS (SELECT 1 FROM invoice_items WHERE invoice_id = i.id)
                          THEN (SELECT COALESCE(SUM(tax_amount), 0) FROM invoice_items WHERE invoice_id = i.id)
                          ELSE i.taxes
                      END AS display_taxes,
                      
                      CASE
                          WHEN EXISTS (SELECT 1 FROM invoice_items WHERE invoice_id = i.id)
                          THEN (SELECT COALESCE(SUM(total_price), 0) FROM invoice_items WHERE invoice_id = i.id)
                          ELSE i.total
                      END AS display_total
                      
                  FROM invoice i
                  LEFT JOIN tenants t ON i.tenant_id = t.id
                  ORDER BY i.created_at DESC
              ");
    $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    $errorMessage = "❌ Failed to fetch expenses: " . $e->getMessage();
    echo  $errorMessage;
}
