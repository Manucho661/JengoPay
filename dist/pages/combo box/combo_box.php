<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Combo Box</title>
    <style>
        .combo-box {
            position: relative;
            width: 250px;
            margin: 20px;
        }
        
        .combo-input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
            padding-right: 40px;
        }
        
        .combo-button {
            position: absolute;
            right: 1px;
            top: 1px;
            height: calc(100% - 2px);
            width: 35px;
            background: #f5f5f5;
            border: none;
            border-left: 1px solid #ddd;
            border-radius: 0 6px 6px 0;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
        }
        
        .combo-options {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            margin: 5px 0 0 0;
            padding: 0;
            list-style: none;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: white;
            z-index: 1000;
            max-height: 300px;
            overflow-y: auto;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            display: none;
        }
        
        .combo-options.show {
            display: block;
        }
        
        .combo-option {
            padding: 10px 15px;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .combo-option:hover {
            background-color: #f0f7ff;
            color: #0066cc;
        }
        
        .combo-option.selected {
            background-color: #e6f2ff;
            font-weight: bold;
        }
        
        .no-results {
            padding: 10px 15px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="combo-box">
        <input type="text" class="combo-input" placeholder="Search or select...">
        <button class="combo-button">▼</button>
        <ul class="combo-options">
            <li class="combo-option" data-value="all">All Rentals</li>
            <li class="combo-option" data-value="active">Active Rentals</li>
            <li class="combo-option" data-value="future">Future Rentals</li>
            <li class="combo-option" data-value="residents">Residents</li>
            <li class="combo-option" data-value="owners">Property Owners</li>
            <li class="combo-option" data-value="overdue">Overdue Payments</li>
            <li class="combo-option" data-value="renewals">Upcoming Renewals</li>
        </ul>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const comboBox = document.querySelector('.combo-box');
            const input = comboBox.querySelector('.combo-input');
            const button = comboBox.querySelector('.combo-button');
            const optionsList = comboBox.querySelector('.combo-options');
            const options = comboBox.querySelectorAll('.combo-option');
            
            // Toggle dropdown
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                optionsList.classList.toggle('show');
                if (optionsList.classList.contains('show')) {
                    input.focus();
                }
            });
            
            // Handle option selection
            options.forEach(option => {
                option.addEventListener('click', function() {
                    input.value = this.textContent;
                    optionsList.classList.remove('show');
                    
                    // Update selected style
                    options.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    
                    // Here you would filter your table/data
                    console.log('Selected:', this.getAttribute('data-value'));
                });
            });
            
            // Filter options while typing
            input.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                let hasVisibleOptions = false;
                
                options.forEach(option => {
                    const optionText = option.textContent.toLowerCase();
                    if (optionText.includes(searchTerm)) {
                        option.style.display = 'block';
                        hasVisibleOptions = true;
                    } else {
                        option.style.display = 'none';
                    }
                });
                
                // Show "no results" if needed
                const noResults = optionsList.querySelector('.no-results');
                if (!hasVisibleOptions) {
                    if (!noResults) {
                        const noResultsElem = document.createElement('li');
                        noResultsElem.className = 'no-results';
                        noResultsElem.textContent = 'No matches found';
                        optionsList.appendChild(noResultsElem);
                    }
                } else if (noResults) {
                    noResults.remove();
                }
                
                optionsList.classList.add('show');
            });
            
            // Close when clicking outside
            document.addEventListener('click', function() {
                optionsList.classList.remove('show');
            });
            
            // Keyboard navigation
            input.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowDown' && !optionsList.classList.contains('show')) {
                    optionsList.classList.add('show');
                }
            });
        });
    </script>
</body>
</html>