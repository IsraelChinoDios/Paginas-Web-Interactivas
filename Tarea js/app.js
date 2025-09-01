// LÓGICA DE JUEGO
const TOTAL_FUNCS = 10;
const used = new Set();
let points = 0;
let gameOver = false;

function updateUI() {
  // Puntos
  document.getElementById('points').textContent = points;

  // Progreso
  const pct = Math.round((used.size / TOTAL_FUNCS) * 100);
  const bar = document.getElementById('progress-bar');
  bar.style.width = pct + '%';
  bar.setAttribute('aria-valuenow', String(pct));
  bar.textContent = pct + '%';

  // Completado
  if (used.size === TOTAL_FUNCS && !gameOver) {
    gameOver = true;
    document.getElementById('finalPoints').textContent = points;
    const modal = new bootstrap.Modal(document.getElementById('winModal'));
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

  // Limpiar
  document.querySelectorAll('.out').forEach(el => el.textContent = '');
}

document.getElementById('reset-btn').addEventListener('click', resetGame);

// FUNCIONES BÁSICA
function suma(a, b) 
    { return Number(a) + Number(b); }

function resta(a, b) 
    { return Number(a) - Number(b); }

function esPar(n) {
  n = Number(n);
  return Number.isFinite(n) && n % 2 === 0;
}

function factorial(n) {
  n = Number(n);
  if (!Number.isInteger(n) || n < 0) throw new Error("n debe ser un entero ≥ 0");
  let r = 1; for (let i = 2; i <= n; i++) r *= i; return r;
}

function invertirTexto(str) 
    { return String(str).split("").reverse().join(""); }

function multiplicar(a, b) 
    { return Number(a) * Number(b); }

function maximo(listaNums) 
    { return listaNums.length ? Math.max(...listaNums) : null; }

function promedio(listaNums) 
    { if(!listaNums.length) return null; const s=listaNums.reduce((a,n)=>a+n,0); return s/listaNums.length; }

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

function parseListaNumeros(cadena){
  return String(cadena)
    .split(/[,\s]+/)
    .map(s=>s.trim())
    .filter(Boolean)
    .map(Number)
    .filter(n=>Number.isFinite(n));
}
function setOut(id, value){
  document.getElementById(id).textContent = String(value);
}

window.addEventListener('DOMContentLoaded', () => {
  // Mostrar instrucciones al inicio
  const introEl = document.getElementById('introModal');
  if (introEl) {
    const intro = new bootstrap.Modal(introEl);
    intro.show();
  }

  // 1) Suma
  document.getElementById('suma-btn').addEventListener('click', () => {
    const a = document.getElementById('suma-a').value;
    const b = document.getElementById('suma-b').value;
    setOut('suma-out', suma(a,b));
    award('suma');
  });

  // 2) Resta
  document.getElementById('resta-btn').addEventListener('click', () => {
    const a = document.getElementById('resta-a').value;
    const b = document.getElementById('resta-b').value;
    setOut('resta-out', resta(a,b));
    award('resta');
  });

  // 3) Es par
  document.getElementById('espar-btn').addEventListener('click', () => {
    const n = document.getElementById('espar-n').value;
    setOut('espar-out', esPar(n) ? 'Sí, es par' : 'No, es impar');
    award('espar');
  });

  // 4) Factorial
  document.getElementById('fact-btn').addEventListener('click', () => {
    const n = document.getElementById('fact-n').value;
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
  document.getElementById('inv-btn').addEventListener('click', () => {
    const t = document.getElementById('inv-txt').value;
    setOut('inv-out', invertirTexto(t));
    award('invertir');
  });

  // 6) Multiplicar
  document.getElementById('mul-btn').addEventListener('click', () => {
    const a = document.getElementById('mul-a').value;
    const b = document.getElementById('mul-b').value;
    setOut('mul-out', multiplicar(a,b));
    award('multiplicar');
  });

  // 7) Máximo
  document.getElementById('max-btn').addEventListener('click', () => {
    const lista = parseListaNumeros(document.getElementById('max-list').value);
    const r = maximo(lista);
    setOut('max-out', r===null ? 'Lista vacía' : r);
    award('maximo', r!==null);
  });

  // 8) Promedio
  document.getElementById('avg-btn').addEventListener('click', () => {
    const lista = parseListaNumeros(document.getElementById('avg-list').value);
    const r = promedio(lista);
    setOut('avg-out', r===null ? 'Lista vacía' : r);
    award('promedio', r!==null);
  });

  // 9) Aleatorio
  document.getElementById('rnd-btn').addEventListener('click', () => {
    const min = document.getElementById('rnd-min').value;
    const max = document.getElementById('rnd-max').value;
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
  document.getElementById('div-btn').addEventListener('click', () => {
    const a = document.getElementById('div-a').value;
    const b = document.getElementById('div-b').value;
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
