const pdfInput = document.getElementById("pdf-upload");
const importForm = document.getElementById("import-form");

pdfInput.addEventListener("change", (event) => {
  importForm.submit();
});
