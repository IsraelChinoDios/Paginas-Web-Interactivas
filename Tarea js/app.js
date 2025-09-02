// ======= LÓGICA DE JUEGO =======
const TOTAL_FUNCS = 10;
const used = new Set();
let points = 0;
let gameOver = false; // congela el puntaje al ganar

function updateUI() {
  // Puntos
  $('#points').text(points);

  // Progreso
  const pct = Math.round((used.size / TOTAL_FUNCS) * 100);
  const $bar = $('#progress-bar');
  $bar.css('width', pct + '%').attr('aria-valuenow', String(pct)).text(pct + '%');

  // Completado (mostrar modal una sola vez y congelar)
  if (used.size === TOTAL_FUNCS && !gameOver) {
    gameOver = true;
    $('#finalPoints').text(points);
    const modal = new bootstrap.Modal($('#winModal')[0]);
    modal.show();
  }
}

function award(functionKey, success = true) {
  if (!success || gameOver) return; // si falló o ya terminó, no sumar
  if (!used.has(functionKey)) {
    used.add(functionKey);
    points += 10; // primer uso de esa función
  }
  updateUI();
}

function resetGame() {
  used.clear();
  points = 0;
  gameOver = false;
  updateUI();
  // Limpiar salidas
  $('.out').text('');
}

// ======= FUNCIONES BÁSICAS =======
function suma(a, b) { return Number(a) + Number(b); }
function resta(a, b) { return Number(a) - Number(b); }
function esPar(n) {
  n = Number(n);
  return Number.isFinite(n) && n % 2 === 0;
}
function factorial(n) {
  n = Number(n);
  if (!Number.isInteger(n) || n < 0) throw new Error("n debe ser un entero ≥ 0");
  let r = 1; for (let i = 2; i <= n; i++) r *= i; return r;
}
function invertirTexto(str) { return String(str).split("").reverse().join(""); }
function multiplicar(a, b) { return Number(a) * Number(b); }
function maximo(listaNums) { return listaNums.length ? Math.max(...listaNums) : null; }
function promedio(listaNums) { if(!listaNums.length) return null; const s=listaNums.reduce((a,n)=>a+n,0); return s/listaNums.length; }
function randomEntero(min,max){
  min=Math.ceil(Number(min)); max=Math.floor(Number(max));
  if(!Number.isFinite(min)||!Number.isFinite(max)||min>max) throw new Error("Rango inválido");
  return Math.floor(Math.random()*(max-min+1))+min;
}
function dividir(a,b){
  const x=Number(a), y=Number(b);
  if(!Number.isFinite(x)||!Number.isFinite(y)) throw new Error("Entrada inválida");
  if(y===0) throw new Error("No se puede dividir entre 0");
  return x/y;
}

// ======= UTILIDADES =======
function parseListaNumeros(cadena){
  return String(cadena)
    .split(/[,\s]+/)
    .map(s=>s.trim())
    .filter(Boolean)
    .map(Number)
    .filter(n=>Number.isFinite(n));
}
function setOut(id, value){
  $('#' + id).text(String(value));
}

// ======= WIRING (jQuery) =======
$(function() {
  // Mostrar instrucciones al inicio
  const introEl = $('#introModal')[0];
  if (introEl) new bootstrap.Modal(introEl).show();

  // Botón Reiniciar
  $('#reset-btn').on('click', resetGame);

  // 1) Suma
  $('#suma-btn').on('click', () => {
    const a = $('#suma-a').val();
    const b = $('#suma-b').val();
    setOut('suma-out', suma(a,b));
    award('suma');
  });

  // 2) Resta
  $('#resta-btn').on('click', () => {
    const a = $('#resta-a').val();
    const b = $('#resta-b').val();
    setOut('resta-out', resta(a,b));
    award('resta');
  });

  // 3) Es par
  $('#espar-btn').on('click', () => {
    const n = $('#espar-n').val();
    setOut('espar-out', esPar(n) ? 'Sí, es par ✅' : 'No, es impar ❌');
    award('espar');
  });

  // 4) Factorial
  $('#fact-btn').on('click', () => {
    const n = $('#fact-n').val();
    try {
      const r = factorial(n);
      setOut('fact-out', r);
      award('factorial');
    } catch (e) {
      setOut('fact-out', `Error: ${e.message}`);
      award('factorial', false);
    }
  });

  // 5) Invertir texto
  $('#inv-btn').on('click', () => {
    const t = $('#inv-txt').val();
    setOut('inv-out', invertirTexto(t));
    award('invertir');
  });

  // 6) Multiplicar
  $('#mul-btn').on('click', () => {
    const a = $('#mul-a').val();
    const b = $('#mul-b').val();
    setOut('mul-out', multiplicar(a,b));
    award('multiplicar');
  });

  // 7) Máximo
  $('#max-btn').on('click', () => {
    const lista = parseListaNumeros($('#max-list').val());
    const r = maximo(lista);
    setOut('max-out', r===null ? 'Lista vacía' : r);
    award('maximo', r!==null);
  });

  // 8) Promedio
  $('#avg-btn').on('click', () => {
    const lista = parseListaNumeros($('#avg-list').val());
    const r = promedio(lista);
    setOut('avg-out', r===null ? 'Lista vacía' : r);
    award('promedio', r!==null);
  });

  // 9) Aleatorio
  $('#rnd-btn').on('click', () => {
    const min = $('#rnd-min').val();
    const max = $('#rnd-max').val();
    try {
      const r = randomEntero(min,max);
      setOut('rnd-out', r);
      award('aleatorio');
    } catch (e) {
      setOut('rnd-out', `Error: ${e.message}`);
      award('aleatorio', false);
    }
  });

  // 10) División
  $('#div-btn').on('click', () => {
    const a = $('#div-a').val();
    const b = $('#div-b').val();
    try {
      const r = dividir(a,b);
      setOut('div-out', r);
      award('division');
    } catch (e) {
      setOut('div-out', `Error: ${e.message}`);
      award('division', false);
    }
  });

  // Inicializa UI
  updateUI();
});
