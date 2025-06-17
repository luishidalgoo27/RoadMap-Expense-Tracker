<?php

const json = __DIR__ . "/expenses.json";

function loader()
{
    if (!file_exists(json)) return [];
    $content = file_get_contents(json);
    $expenses = json_decode($content, true);
    if (!is_array($expenses)) return [];
    
    return $expenses;
}

function save(array $expenses)
{
    file_put_contents(json, json_encode($expenses, JSON_PRETTY_PRINT));
}

function getNextId(array $expenses)
{
    $expenses = loader();
    if (empty($expenses)) return 1;
    $ids = array_column($expenses, 'id');
    return max($ids) + 1;
}

function addExpense(string $description, int $amount)
{
    $expenses = loader();
    $id = getNextId($expenses);
    $newExpense = store($expenses, $id, $description, $amount);
    $expenses[] = $newExpense;
    save($expenses);
    echo "Expense added successfully (ID: $id)";
}

function store(array $expenses, int $id, string $description, int $amount)
{
    $date = date('Y-m-d');
    return [
        'id' => $id,
        'date' => $date,
        'description' => $description,
        'amount' => $amount,
    ];
}

function listExpenses()
{
    $expenses = loader();
    // # al lado de cada fila %-3s string 3 caracteres alineado a la izquierda, %s string (sin limite)
    printf("# %-3s %-12s %-15s %s\n", "ID", "DATE", "DESCRIPTION", "AMOUNT");
    foreach($expenses as $expense)
    {
        printf(
            "# %-3d %-12s %-15s \$%d\n",
            $expense['id'],
            $expense['date'],
            $expense['description'],
            $expense['amount']
        );
    }
}

function sumAmount()
{
    $expenses = loader();
    $amountColumn = array_column($expenses, 'amount');
    $amounts = array_sum($amountColumn);
    echo "Total expenses: $$amounts";
}

function deleteExpense(int $id)
{
    $expenses = loader();
    foreach($expenses as $i => $expense)
    {
        if($expense['id'] == $id){
            $index = $i;
            break;
        }
    }
    
    if($index === null){
        echo "❌ ID not found.\n";
        return;
    }

    array_splice($expenses, $id, 1);
    save($expenses);

    echo "Expense deleted successfully";
}

function sumAmountForMonth($monthCLI)
{
    $expenses = loader();
    $total = 0;
    foreach ($expenses as $expense) {
        $month = (int)substr($expense['date'], 5, 2);
        if ($month == (int)$monthCLI) {
            $total += $expense['amount'];
        }
    }
    $monthName = getMonthName($monthCLI);
    echo "Total expenses for $monthName: $$total\n";
}

function getMonthName($monthNumber) {
    $months = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
    ];
    return $months[(int)$monthNumber] ?? 'Unknown';
}

global $argc, $argv;

if($argc < 2){
    echo "Use: \n";
    echo "php ExpenseTracker add \n";
}

$command = $argv[1];

// Parseamos las opciones (a partir de $argv[2])
$options = [];
for ($i = 2; $i < $argc; $i++) {
    // Si es una opción (empieza por --)
    if (substr($argv[$i], 0, 2) === '--') {
        // quitamos el "--"
        $key = substr($argv[$i], 2);
        // valor de la opción (lo que viene después)
        $value = (isset($argv[$i + 1]) && substr($argv[$i + 1], 0, 2) !== '--') 
        ? $argv[$i + 1] 
        : null;
        // guardamos en el array $options
        $options[$key] = $value;
        $i++; // saltamos al siguiente par clave/valor
    }
}

switch($command)
{
     case 'add':
        // Revisamos si se pasaron --description y --amount
        if (empty($options['description']) || empty($options['amount'])) {
            echo "Error: --description and --amount are required.\n";
            exit(1);
        }

        // Llamamos a la función addExpense()
        addExpense($options['description'], (int)$options['amount']);
        break;

    case 'list':
        listExpenses();
        break;
        
    case 'summary':
        if (empty($options['month'])) {
            sumAmount();
        } else {
            sumAmountForMonth($options['month']);
        }
        break;

    case 'delete':
        if (empty($options['id'])) {
            echo "Error: --id is required.\n";
            exit(1);
        }

        deleteExpense((int)$options['id']);
        break;

    default:
        echo "Unknown command: $command\n";
        exit(1);
}

?>