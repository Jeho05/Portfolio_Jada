(function(){
  const qs = (s, el=document) => el.querySelector(s);
  const qsa = (s, el=document) => Array.from(el.querySelectorAll(s));
  const csrf = () => (qs('meta[name="csrf-token"]')||{}).content || '';

  // Tabs
  qsa('.tab').forEach(btn => {
    btn.addEventListener('click', () => {
      qsa('.tab').forEach(b => b.classList.remove('active'));
      qsa('.tab-panel').forEach(p => p.classList.remove('active'));
      btn.classList.add('active');
      const panel = qs('#' + btn.dataset.tab);
      if (panel) panel.classList.add('active');
    });
  });

  // Remove handlers (delegated)
  document.addEventListener('click', (e) => {
    if (e.target.matches('.remove')){
      const item = e.target.closest('.item');
      if (item) item.remove();
    }
  });

  // Helpers to compute next index to avoid collisions after deletions
  function nextIndex(prefix){
    const all = qsa(`[name^="${prefix}["]`);
    const used = new Set();
    all.forEach(inp => {
      const m = inp.name.match(/^\Q${prefix}\E\[(\d+)\]/);
      if (m) used.add(parseInt(m[1], 10));
    });
    let i = 0; while (used.has(i)) i++;
    return i;
  }

  // Add Skill
  const skillsWrap = qs('.skills-list');
  const tplSkill = qs('#tpl-skill');
  qs('.add-skill')?.addEventListener('click', () => {
    const idx = nextIndex('skills');
    const node = tplSkill.content.cloneNode(true);
    const inputs = qsa('input', node);
    inputs[0].setAttribute('name', `skills[${idx}][name]`);
    inputs[1].setAttribute('name', `skills[${idx}][level]`);
    skillsWrap.appendChild(node);
  });

  // Add Project
  const projectsWrap = qs('.projects-list');
  const tplProject = qs('#tpl-project');
  qs('.add-project')?.addEventListener('click', () => {
    const idx = nextIndex('projects');
    const node = tplProject.content.cloneNode(true);
    const fields = qsa('input, textarea', node);
    // order in template: title(input), url(input), description(textarea), tags(input), hidden image(input), file(input)
    fields[0].setAttribute('name', `projects[${idx}][title]`);
    fields[1].setAttribute('name', `projects[${idx}][url]`);
    fields[2].setAttribute('name', `projects[${idx}][description]`);
    fields[3].setAttribute('name', `projects[${idx}][tags]`);
    fields[4].setAttribute('name', `projects[${idx}][image]`); // hidden
    projectsWrap.appendChild(node);
    bindImageInputs(projectsWrap.lastElementChild);
  });

  // Image uploads
  async function uploadFile(file){
    const fd = new FormData();
    fd.append('file', file);
    fd.append('csrf_token', csrf());
    const r = await fetch('upload.php', {method:'POST', body: fd});
    const j = await r.json().catch(() => ({error:'Réponse invalide'}));
    if (!r.ok || j.error) throw new Error(j.error || 'Échec du téléversement');
    return j.path;
  }

  function bindImageInputs(scope=document){
    qsa('.image-input', scope).forEach(box => {
      const fileInput = qs('.file', box);
      const btn = qs('.upload-btn', box);
      const hidden = qsa('input[type="hidden"]', box)[0];
      const preview = qs('.preview', box);
      if (!fileInput || !btn || !hidden || !preview) return;

      // Prevent double binding
      if (btn.dataset.bound) return; btn.dataset.bound = '1';

      btn.addEventListener('click', () => fileInput.click());
      fileInput.addEventListener('change', async () => {
        if (!fileInput.files || !fileInput.files[0]) return;
        btn.disabled = true; const old = btn.textContent; btn.textContent = 'Téléversement...';
        try{
          const path = await uploadFile(fileInput.files[0]);
          hidden.value = path;
          preview.src = '../' + path;
        }catch(err){
          alert(err.message || 'Erreur lors du téléversement');
        }finally{
          btn.disabled = false; btn.textContent = old;
          fileInput.value = '';
        }
      });
    });
  }

  // Initial bind
  bindImageInputs();
})();
