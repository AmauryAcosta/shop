function sortTable(columnIndex) {
  const table = document.getElementById("productTable");
  const rows = Array.from(table.rows);
  const isNumeric = columnIndex === 2;

  rows.sort((a, b) => {
    const aText = a.cells[columnIndex].innerText;
    const bText = b.cells[columnIndex].innerText;

    return isNumeric
      ? parseFloat(aText) - parseFloat(bText)
      : aText.localeCompare(bText);
  });

  rows.forEach((row) => table.appendChild(row));
}
