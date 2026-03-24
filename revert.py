import os

replacements = {
    '(--brand-yellow)': '[var(--brand-yellow)]',
    '(--brand-dark)': '[var(--brand-dark)]',
    '(--brand-grey)': '[var(--brand-grey)]',
    'bg-linear-to-b': 'bg-gradient-to-b',
    'bg-linear-to-br': 'bg-gradient-to-br',
    'bg-linear-to-t': 'bg-gradient-to-t',
    'aspect-4/5': 'aspect-[4/5]',
    'tracking-widest': 'tracking-[0.1em]',
    'bottom-10!': '!bottom-10'
}

files = [
    r"c:\Users\jever\OneDrive\Área de Trabalho\Desk-Back-Front\frontend\index.html",
    r"c:\Users\jever\OneDrive\Área de Trabalho\Desk-Back-Front\frontend\input.css",
    r"c:\Users\jever\OneDrive\Área de Trabalho\Desk-Back-Front\frontend\pages\carrinho.html",
    r"c:\Users\jever\OneDrive\Área de Trabalho\Desk-Back-Front\frontend\pages\catalogo.html",
    r"c:\Users\jever\OneDrive\Área de Trabalho\Desk-Back-Front\frontend\pages\checkout.html",
    r"c:\Users\jever\OneDrive\Área de Trabalho\Desk-Back-Front\frontend\pages\login.html",
    r"c:\Users\jever\OneDrive\Área de Trabalho\Desk-Back-Front\frontend\pages\produto.html",
    r"c:\Users\jever\OneDrive\Área de Trabalho\Desk-Back-Front\frontend\pages\sucesso.html",
    r"c:\Users\jever\OneDrive\Área de Trabalho\Desk-Back-Front\frontend\assets\js\utils.js",
    r"c:\Users\jever\OneDrive\Área de Trabalho\Desk-Back-Front\frontend\assets\js\categorias.js"
]

for file_path in files:
    if os.path.exists(file_path):
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        for old, new in replacements.items():
            content = content.replace(old, new)
            
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(content)
            
print("Reverted Tailwind v4 back to v3 successfully!")
