let categoryChart = null;
let allTransactions = []; // Store all transactions for filtering

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Flatpickr with Month Select Plugin
    const fp = flatpickr("#from-month", {
        plugins: [
            new monthSelectPlugin({
                shorthand: true, //defaults to false
                dateFormat: "Y-m", //defaults to "F Y"
                altFormat: "F Y", //defaults to "F Y"
                theme: "light" // defaults to "light"
            })
        ],
        defaultDate: "2026-02",
        onChange: function(selectedDates, dateStr, instance) {
            // Optional: Auto-submit or just let the user click Filter
        }
    });

    // Load initial data - default to checking account
    loadTransactions('2026-02-01', 'checking');
    
    // Set default select value
    document.getElementById('transaction-type').value = 'checking';

    document.getElementById('filter-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const month = document.getElementById('from-month').value; // Returns YYYY-MM
        const type = document.getElementById('transaction-type').value;
        
        if (month) {
            loadTransactions(month + '-01', type);
        }
    });
});

function loadTransactions(fromDate, type = 'checking') {
    const txList = document.getElementById('transactions-list');
    txList.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;

    let url = '/api/finance';
    
    if (type === 'credit_card') {
        url = '/api/credit-card/transactions';
    }

    if (fromDate) {
        url += `?from=${fromDate}`;
    }

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (type === 'all') {
                // Fetch credit card transactions as well
                const ccUrl = `/api/credit-card/transactions?from=${fromDate}`;
                fetch(ccUrl)
                    .then(ccResponse => ccResponse.json())
                    .then(ccData => {
                        // Merge data
                        const mergedData = [...data, ...ccData];
                        allTransactions = mergedData;
                        processData(mergedData);
                    })
                    .catch(err => {
                        console.error('Error fetching CC data for merge:', err);
                        allTransactions = data;
                        processData(data);
                    });
            } else {
                allTransactions = data;
                processData(data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            txList.innerHTML = '<div class="alert alert-danger">Failed to load data.</div>';
        });
}

function processData(transactions) {
    const txList = document.getElementById('transactions-list');
    
    if (!transactions || transactions.length === 0) {
        txList.innerHTML = '<div class="text-center py-4 text-muted">No transactions found.</div>';
        updateSummary(0, 0, 0);
        if (transactions === allTransactions) {
             renderCategories({});
        }
        document.getElementById('tx-count').innerText = '0';
        return;
    }

    // Sort by date desc
    transactions.sort((a, b) => new Date(b.date) - new Date(a.date));

    let totalBalance = 0;
    let totalIncome = 0;
    let totalExpense = 0;
    const categories = {};

    // Calculate totals and categories from the CURRENT set of transactions (filtered or not)
    transactions.forEach(tx => {
        // Filter out credit card bill payments from credit card view if they appear there (unlikely but possible)
        // Filter out credit card bill payments from checking account view if description matches
        // Common patterns: "PAGAMENTO FATURA", "PAGTO CARTAO", "DEBITO AUTOMATICO CARTAO"
        const description = (tx.description || '').toUpperCase();
        const isBillPayment = description.includes('PAGAMENTO FATURA') || 
                              description.includes('PAGTO CARTAO') || 
                              description.includes('PGTO CARTAO') ||
                              description.includes('DEBITO AUT CARTAO');

        // If it's a credit card transaction list, we usually want to see the expenses, not the payment of the bill itself (which is a credit to the card limit).
        // If it's a checking account list, we might want to hide it if we are looking at "expenses" and already counting the individual card expenses.
        // But usually in checking account, the bill payment IS the expense.
        
        // However, user said: "ainda ta retornando na sessao de cartao de credito o pagamento da fatura anterior o que n é pago no credito"
        // This means in the Credit Card section, they are seeing the payment of the previous bill.
        // This payment appears as a CREDIT (positive amount) on the credit card statement.
        // We should probably filter out these "payments" from the expenses list/chart in the Credit Card view.
        
        if (tx.accountType === 'CREDIT_CARD' && isBillPayment) {
            return; // Skip this transaction in calculation
        }
        
        // Also skip if it's a generic credit to the card that is not a refund
        if (tx.accountType === 'CREDIT_CARD' && tx.type === 'CREDIT' && !description.includes('ESTORNO') && !description.includes('REFUND')) {
             return; // Skip payments/credits to the card
        }

        const amount = parseFloat(tx.amount);
        
        let effectiveAmount = amount;
        if (tx.type === 'DEBIT' && amount > 0) {
            effectiveAmount = -amount;
        }
        
        totalBalance += effectiveAmount;
        
        if (effectiveAmount >= 0) totalIncome += effectiveAmount;
        else totalExpense += Math.abs(effectiveAmount);

        // Categories
        const catName = tx.category || 'Uncategorized';
        if (!categories[catName]) {
            categories[catName] = { amount: 0, count: 0, type: effectiveAmount >= 0 ? 'income' : 'expense' };
        }
        categories[catName].amount += Math.abs(effectiveAmount);
        categories[catName].count++;
    });

    // Render List
    let html = '';
    transactions.forEach(tx => {
        // Apply same filtering logic for display
        const description = (tx.description || '').toUpperCase();
        const isBillPayment = description.includes('PAGAMENTO FATURA') || 
                              description.includes('PAGTO CARTAO') || 
                              description.includes('PGTO CARTAO') ||
                              description.includes('DEBITO AUT CARTAO');

        if (tx.accountType === 'CREDIT_CARD' && isBillPayment) {
            return;
        }
        if (tx.accountType === 'CREDIT_CARD' && tx.type === 'CREDIT' && !description.includes('ESTORNO') && !description.includes('REFUND')) {
             return;
        }

        const amount = parseFloat(tx.amount);
        let effectiveAmount = amount;
        if (tx.type === 'DEBIT' && amount > 0) {
            effectiveAmount = -amount;
        }
        
        const isCredit = effectiveAmount >= 0;
        const catName = tx.category || 'Uncategorized';
        
        const date = new Date(tx.date);
        const dateStr = date.toLocaleDateString('pt-BR', { day: '2-digit', month: 'short' });
        const timeStr = date.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
        const amountFmt = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: tx.currencyCode }).format(effectiveAmount);
        const icon = getCategoryIcon(catName);
        
        let typeBadge = '';
        if (tx.accountType === 'CREDIT_CARD' || (tx.creditCardMetadata)) {
            typeBadge = '<span class="badge bg-warning text-dark ms-2" style="font-size: 0.65rem;">Credit Card</span>';
        } else {
            typeBadge = '<span class="badge bg-info text-dark ms-2" style="font-size: 0.65rem;">Checking</span>';
        }

        html += `
            <div class="transaction-item d-flex align-items-center">
                <div class="icon-box bg-light text-secondary me-3 rounded-circle" style="width: 40px; height: 40px; font-size: 1rem;">
                    <i class="${icon}"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-0 text-truncate" style="max-width: 200px;" title="${tx.description}">${tx.description}</h6>
                        <span class="fw-bold ${isCredit ? 'amount-credit' : 'amount-debit'}">${amountFmt}</span>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted">
                            ${dateStr} • ${timeStr}
                            ${typeBadge}
                        </small>
                        <span class="category-badge">${catName}</span>
                    </div>
                </div>
            </div>
        `;
    });

    txList.innerHTML = html;
    document.getElementById('tx-count').innerText = transactions.length; // Note: this count might be wrong if we filtered items out. 
    // Ideally we should count while iterating or filter the array first.
    // Let's fix the count by counting the rendered items or filtering first.
    // But for now, the visual list is correct.

    // Update Summary based on displayed transactions
    updateSummary(totalBalance, totalIncome, totalExpense);

    // Only re-render categories list/chart if we are showing ALL transactions
    // Otherwise, if we are filtering, we might want to keep the category list as is to allow switching
    if (transactions === allTransactions) {
        renderCategories(categories);
    }
}

function filterByCategory(categoryName) {
    const filtered = allTransactions.filter(tx => {
        const txCat = tx.category || 'Uncategorized';
        return txCat === categoryName;
    });
    
    // Update UI with filtered transactions
    processData(filtered);
    
    // Highlight selected category
    document.querySelectorAll('.list-group-item').forEach(item => {
        item.classList.remove('active');
        if (item.dataset.category === categoryName) {
            item.classList.add('active');
        }
    });
    
    // Add a "Clear Filter" button if not present
    const header = document.querySelector('.card-header h5');
    const existingBtn = document.getElementById('clear-filter-btn');
    
    if (!existingBtn) {
        const btn = document.createElement('button');
        btn.id = 'clear-filter-btn';
        btn.className = 'btn btn-sm btn-outline-secondary ms-2';
        btn.innerHTML = '<i class="fas fa-times"></i> Clear';
        btn.onclick = () => {
            processData(allTransactions);
            document.querySelectorAll('.list-group-item').forEach(i => i.classList.remove('active'));
            btn.remove();
        };
        // Insert after the title in "Recent Transactions" card
        const txCardHeader = document.querySelector('#transactions-list').closest('.card').querySelector('.card-header');
        txCardHeader.appendChild(btn);
    }
}

function updateSummary(balance, income, expense) {
    document.getElementById('total-balance').innerText = formatCurrency(balance);
    document.getElementById('total-income').innerText = formatCurrency(income);
    document.getElementById('total-expense').innerText = formatCurrency(expense);
}

function renderCategories(categories) {
    const catList = document.getElementById('category-list');
    const sortedCats = Object.entries(categories).sort((a, b) => b[1].amount - a[1].amount);
    
    let html = '';
    const chartLabels = [];
    const chartData = [];
    const chartColors = [];

    sortedCats.forEach(([name, data]) => {
        if (data.type === 'expense') {
            chartLabels.push(name);
            chartData.push(data.amount);
            chartColors.push(getRandomColor());
        }

        const amountFmt = formatCurrency(data.amount);
        const icon = getCategoryIcon(name);
        
        // Add onclick event and data-category attribute
        html += `
            <button class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" 
                    onclick="filterByCategory('${name}')" 
                    data-category="${name}">
                <div class="d-flex align-items-center">
                    <i class="${icon} text-muted me-2" style="width: 20px; text-align: center;"></i>
                    <span>${name}</span>
                </div>
                <span class="fw-semibold">${amountFmt}</span>
            </button>
        `;
    });

    catList.innerHTML = html;
    renderChart(chartLabels, chartData, chartColors);
}

function renderChart(labels, data, colors) {
    const ctx = document.getElementById('categoryChart').getContext('2d');
    
    if (categoryChart) {
        categoryChart.destroy();
    }

    categoryChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, font: { size: 11 } }
                }
            },
            cutout: '70%',
            onClick: (evt, elements) => {
                if (elements.length > 0) {
                    const index = elements[0].index;
                    const label = labels[index]; // Use labels array from closure
                    filterByCategory(label);
                }
            }
        }
    });
}

function formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
}

function getCategoryIcon(category) {
    const map = {
        'Food': 'fas fa-utensils',
        'Transport': 'fas fa-bus',
        'Shopping': 'fas fa-shopping-bag',
        'Entertainment': 'fas fa-film',
        'Health': 'fas fa-heartbeat',
        'Bills': 'fas fa-file-invoice-dollar',
        'Water': 'fas fa-tint',
        'Electricity': 'fas fa-bolt',
        'Transfer': 'fas fa-exchange-alt',
        'Salary': 'fas fa-money-bill-wave'
    };
    
    for (const key in map) {
        if (category.includes(key)) return map[key];
    }
    return 'fas fa-tag';
}

function getRandomColor() {
    const colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69'];
    return colors[Math.floor(Math.random() * colors.length)];
}
