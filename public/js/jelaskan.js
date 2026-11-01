/* Mode Jelaskan Bubee — ON: klik apa pun memunculkan penjelasannya dulu. */
(function () {
  var KEY = 'bubee_jelaskan';
  var CACHE = null;
  function on() { try { return localStorage.getItem(KEY) === '1'; } catch (e) { return false; } }

  var css = '.jelaskan-veil{position:fixed;inset:0;z-index:90;background:rgba(5,11,46,.55);display:flex;align-items:center;justify-content:center;padding:20px}'
    + '.jelaskan-card{width:100%;max-width:420px;background:#fff;border-radius:22px;padding:20px 18px;box-shadow:0 20px 60px rgba(0,0,0,.4);font-size:13px;max-height:80dvh;overflow-y:auto}'
    + '.jelaskan-tips{margin-top:8px;font-size:12px;background:#FFFBEB;border:1px solid #FDE68A;border-radius:12px;padding:8px 10px}'
    + '.jelaskan-btns{display:flex;gap:8px;margin-top:12px}'
    + '.jelaskan-btns button{flex:1;border:none;border-radius:14px;padding:11px;font-size:13px;font-weight:800;cursor:pointer}'
    + '.jelaskan-go{background:linear-gradient(90deg,#0B2A6B,#00B8D4);color:#fff}'
    + '.jelaskan-no{background:#f1f5f9}';
  var st = document.createElement('style'); st.textContent = css; document.head.appendChild(st);

  function norm(path) { return (path || '/').split('?')[0].replace(/\/\d+(?=\/|$)/g, '/{id}'); }
  async function kamus() {
    if (CACHE) return CACHE;
    try { var r = await fetch('/panduan'); CACHE = await r.json(); }
    catch (e) { CACHE = {}; }
    return CACHE;
  }
  function tampilkan(judul, isi, tips, lanjut) {
    document.querySelectorAll('.jelaskan-veil').forEach(function (e) { e.remove(); });
    var v = document.createElement('div');
    v.className = 'jelaskan-veil';
    v.innerHTML = '<div class="jelaskan-card"><b>💡 ' + judul + '</b>'
      + '<div style="color:#475569;margin-top:6px">' + isi + '</div>'
      + (tips ? '<div class="jelaskan-tips">💡 Tips: ' + tips + '</div>' : '')
      + '<div class="jelaskan-btns"><button class="jelaskan-no">Tutup</button><button class="jelaskan-go">Jalankan ▶</button></div></div>';
    document.body.appendChild(v);
    v.querySelector('.jelaskan-no').onclick = function () { v.remove(); };
    v.addEventListener('click', function (e) { if (e.target === v) v.remove(); });
    v.querySelector('.jelaskan-go').onclick = function () { v.remove(); lanjut(); };
  }

  var melewati = false;
  document.addEventListener('click', async function (e) {
    if (!on() || melewati) return;
    if (e.target.closest('.jelaskan-veil,#jelaskanToggle')) return;
    var a = e.target.closest('a[href]');
    var btn = e.target.closest('button');
    var form = btn ? btn.closest('form') : null;
    var kunci = null, lanjut = null;
    if (form && btn && (btn.type === 'submit' || !btn.type)) {
      var m = form.querySelector('input[name="_method"]');
      var method = ((m && m.value) || form.method || 'GET').toUpperCase();
      var action = form.getAttribute('action') || location.pathname;
      if (/^https?:\/\//i.test(action) && action.indexOf(location.origin) !== 0) {
        kunci = 'LINK_KELUAR';
        lanjut = function () { melewati = true; form.requestSubmit(btn); melewati = false; };
      } else {
        kunci = method + ' ' + norm(new URL(action, location.origin).pathname);
        lanjut = function () { melewati = true; form.requestSubmit(btn); melewati = false; };
      }
    } else if (a) {
      var href = a.getAttribute('href');
      if (!href || href.charAt(0) === '#') return;
      if (/^https?:\/\//i.test(href) && href.indexOf(location.origin) !== 0) {
        kunci = 'LINK_KELUAR';
        lanjut = function () { window.open(href, a.target || '_self'); };
      } else {
        kunci = 'GET ' + norm(new URL(href, location.origin).pathname);
        lanjut = function () { location.href = href; };
      }
    } else return;
    e.preventDefault();
    e.stopPropagation();
    var k = await kamus();
    var p = k[kunci];
    if (p) tampilkan(p.judul, p.isi, p.tips, lanjut);
    else tampilkan('Menuju ' + kunci, 'Membuka halaman/menjalankan aksi ini. Tidak ada penjelasan khusus yang ditulis admin.', null, lanjut);
  }, true);

  window.JelaskanToggle = function () {
    try {
      var v = on() ? '0' : '1';
      localStorage.setItem(KEY, v);
      document.querySelectorAll('#jelaskanToggle').forEach(function (b) { b.dataset.on = v; });
      if (v === '1') alert('💡 Mode Jelaskan AKTIF — klik apa pun untuk melihat penjelasannya dulu.');
    } catch (e) {}
  };
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('#jelaskanToggle').forEach(function (b) { b.dataset.on = on() ? '1' : '0'; });
  });
})();
