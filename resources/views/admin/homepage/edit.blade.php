@extends('layouts.admin')

@section('title', 'Éditer la Page d\'Accueil')

@section('content')
<div class="homepage-editor-container">
    <div class="editor-header">
        <h1>Personnaliser la Page d'Accueil</h1>
        <div class="header-actions">
            <a href="{{ route('admin.homepage.preview') }}" target="_blank" class="btn btn-secondary">
                👁️ Prévisualiser
            </a>
            <button type="submit" form="homepage-form" class="btn btn-primary">
                💾 Enregistrer
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form id="homepage-form" method="POST" action="{{ route('admin.homepage.update') }}">
        @csrf
        @method('PUT')

        <div class="editor-layout">
            <!-- HTML Editor -->
            <div class="editor-panel">
                <div class="editor-panel-header">
                    <h3>📄 HTML</h3>
                    <span class="editor-hint">Utilisez les variables Blade comme {{ "{{ \$stats['total_clients'] }}" }}</span>
                </div>
                <textarea 
                    name="html" 
                    id="html-editor" 
                    class="code-editor"
                    rows="20"
                    required
                >{{ old('html', $html) }}</textarea>
            </div>

            <!-- CSS Editor -->
            <div class="editor-panel">
                <div class="editor-panel-header">
                    <h3>🎨 CSS</h3>
                    <span class="editor-hint">Styles personnalisés pour votre page d'accueil</span>
                </div>
                <textarea 
                    name="css" 
                    id="css-editor" 
                    class="code-editor"
                    rows="20"
                    required
                >{{ old('css', $css) }}</textarea>
            </div>
        </div>

        <!-- Variables disponibles -->
        <div class="variables-info">
            <h3>📊 Variables Disponibles</h3>
            <div class="variables-grid">
                <code>{{ "{{ \$stats['total_clients'] }}" }}</code>
                <code>{{ "{{ \$stats['active_services'] }}" }}</code>
                <code>{{ "{{ \$stats['pending_orders'] }}" }}</code>
                <code>{{ "{{ \$stats['open_tickets'] }}" }}</code>
                <code>{{ "{{ \$stats['unpaid_invoices'] }}" }}</code>
                <code>{{ "{{ \$stats['monthly_revenue'] }}" }}</code>
            </div>
        </div>
    </form>
</div>

<style>
.homepage-editor-container {
    max-width: 1600px;
    margin: 0 auto;
    padding: 2rem;
}

.editor-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #000;
}

.editor-header h1 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    color: #000;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 4px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: all 0.2s;
}

.btn-primary {
    background: #000;
    color: #fff;
}

.btn-primary:hover {
    background: #333;
}

.btn-secondary {
    background: #fff;
    color: #000;
    border: 2px solid #000;
}

.btn-secondary:hover {
    background: #f0f0f0;
}

.alert {
    padding: 1rem;
    border-radius: 4px;
    margin-bottom: 1.5rem;
}

.alert-success {
    background: #d4edda;
    border: 2px solid #28a745;
    color: #155724;
}

.editor-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.editor-panel {
    background: #f8f8f8;
    border: 2px solid #000;
    border-radius: 8px;
    overflow: hidden;
}

.editor-panel-header {
    background: #000;
    color: #fff;
    padding: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.editor-panel-header h3 {
    margin: 0;
    font-size: 1.125rem;
}

.editor-hint {
    font-size: 0.75rem;
    color: #ccc;
}

.code-editor {
    width: 100%;
    padding: 1rem;
    border: none;
    background: #fff;
    font-family: 'Courier New', Courier, monospace;
    font-size: 0.875rem;
    line-height: 1.6;
    resize: vertical;
    min-height: 500px;
}

.code-editor:focus {
    outline: none;
    background: #fafafa;
}

.variables-info {
    background: #fff;
    border: 2px solid #000;
    border-radius: 8px;
    padding: 1.5rem;
}

.variables-info h3 {
    margin: 0 0 1rem 0;
    font-size: 1.125rem;
    color: #000;
}

.variables-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 0.75rem;
}

.variables-grid code {
    background: #f0f0f0;
    padding: 0.5rem;
    border-radius: 4px;
    font-size: 0.875rem;
    border: 1px solid #ddd;
}

@media (max-width: 1024px) {
    .editor-layout {
        grid-template-columns: 1fr;
    }
    
    .editor-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
}
</style>
@endsection
