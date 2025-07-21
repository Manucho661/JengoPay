<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Draft Invoice</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->
     <style>
      body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    background-color: #f4f4f4;
}

.invoice {
    background: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    max-width: 800px;
    margin: auto;
}

h1, h2 {
    text-align: center;
}

h1 {
    font-size: 24px;
    margin-bottom: 10px;
}

h2 {
    font-size: 20px;
    margin-bottom: 20px;
}

p {
    margin: 5px 0;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

tbody tr:hover {
    background-color: #f1f1f1;
}

.summary {
    margin-top: 20px;
    border-top: 2px solid #000;
    padding-top: 10px;
}

.summary h3 {
    text-align: right;
}

footer {
    text-align: center;
    margin-top: 20px;
    font-size: 0.9em;
}

     </style>
</head>
<body>
    <div class="invoice">
        <h1>Abigael Michael</h1>
        <h2>Draft Invoice</h2>
        <p>Invoice Number: <strong>INV/2025/00013</strong></p>
        <p>Invoice Date: <strong>07/16/2025</strong></p>
        <p>Payment Communication: <strong>INV/2025/00013</strong> on this account: <strong>95960200000555 - BANK OF BARODA</strong></p>

        <table>
            <thead>
                <tr>
                    <th>DESCRIPTION</th>
                    <th>QUANTITY</th>
                    <th>UNIT PRICE</th>
                    <th>TAXES</th>
                    <th>AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Rental Income</td>
                    <td>1.00</td>
                    <td>KSh 20,000.00</td>
                    <td>16%</td>
                    <td>KSh 20,000.00</td>
                </tr>
                <tr>
                    <td>Garbage</td>
                    <td>1.00</td>
                    <td>KSh 5,000.00</td>
                    <td>0%</td>
                    <td>KSh 5,000.00</td>
                </tr>
                <tr>
                    <td>Water Charges</td>
                    <td>1.00</td>
                    <td>KSh 1,000.00</td>
                    <td>Exempt</td>
                    <td>KSh 1,000.00</td>
                </tr>
                <tr>
                    <td>Electricity</td>
                    <td>1.00</td>
                    <td>KSh 5,000.00</td>
                    <td>16%</td>
                    <td>KSh 5,000.00</td>
                </tr>
            </tbody>
        </table>

        <div class="summary">
            <p>Untaxed Amount: <strong>KSh 31,000.00</strong></p>
            <p>VAT 16% on KSh 25,000.00: <strong>KSh 4,000.00</strong></p>
            <p>VAT 0% on KSh 6,000.00: <strong>KSh 0.00</strong></p>
            <h3>Total: <strong>KSh 35,000.00</strong></h3>
        </div>

        <footer>
            <p>Your Cargo, Our Priority</p>
            <p>COBBY LOGISTICS COMPANY LIMITED</p>
            <p>P.O Box 29332-00625</p>
            <p>Nairobi West District, Kenya</p>
            <p>Email: <a href="mailto:cobbylogisticscompanylimited@gmail.com">cobbylogisticscompanylimited@gmail.com</a></p>
            <p>Phone: P052305684L</p>
        </footer>
    </div>
</body>
</html>
