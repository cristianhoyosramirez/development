
const edit_retiro_de_efectivo =
  document.querySelector("#cambiar_valor_apertura");

function formatNumber(n) {
  n = String(n).replace(/\D/g, "");
  return n === "" ? n : Number(n).toLocaleString();
}
edit_retiro_de_efectivo.addEventListener("keyup", (e) => {
  const element = e.target;
  const value = element.value;
  element.value = formatNumber(value);
});
