# Expense Tracker CLI (PHP)

Una aplicación sencilla de seguimiento de gastos en línea de comandos para ayudarte a gestionar tus finanzas personales realizada con **PHP**.

## Características

- Añadir gastos con descripción y monto.
- Actualizar gastos existentes.
- Eliminar gastos.
- Listar todos los gastos.
- Ver un resumen total de gastos.
- Ver un resumen de gastos por mes específico.


## Requisitos

- PHP 7.4 o superior.

## Instalación

Clona este repositorio:

```bash
git clone https://github.com/luishidalgoo27/RoadMap-Expense-Tracker
cd RoadMap-Expense-Tracker
```

## Uso

```bash
php ExpenseTracker.php [comando] [opciones]
```

### Ejemplos de comandos

```bash
# Añadir un gasto
php ExpenseTracker.php add --description "Lunch" --amount 20

# Listar gastos
php ExpenseTracker.php list

# Ver resumen total
php ExpenseTracker.php summary

# Eliminar un gasto por ID
php ExpenseTracker.php delete --id 2

# Ver resumen de un mes (por número de mes: 1-12)
php ExpenseTracker.php summary --month 8
```

### Salida esperada

```text
$ expense-tracker add --description "Lunch" --amount 20
# Expense added successfully (ID: 1)

$ expense-tracker list
# ID  Date        Description  Amount
# 1   2024-08-06  Lunch        $20

$ expense-tracker summary
# Total expenses: $20
```

## Estructura de datos

Los gastos se almacenan en un archivo de texto en formato JSON, CSV u otro que especifiques (por defecto, `expenses.json`).

Cada gasto incluye:

- ID único
- Fecha
- Descripción
- Monto
