<?php

namespace App\Livewire\Posts;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\Post;
use Illuminate\Support\Facades\Gate;
use App\Livewire\Wireable\PostData; #Interface

class Posts extends Component
{
    use WithPagination;

    public PostData $postData;

    public bool $showModal = false;
    public string $modalTitle = '';
    public string $confirmationPostId = '';

    protected $listeners = ['refreshPosts' => '$refresh'];

    protected function rules()
    {
        return [
            'postData.title' => 'required|min:5|max:255',
            'postData.content' => 'required|min:10',
            'postData.is_published' => 'boolean',
        ];
    }

    public function mount()
    {
        $this->postData = new PostData();
        // sleep(2); // Simula um carregamento lento Lazy
        $this->js("console.log('live live Livewire...')");
    }

    #[On('post-created')]
    public function refreshList()
    {
        $this->resetPage();
    }

    public function create()
    {
        Gate::authorize('create', Post::class);
        // $this->authorize('create', Post::class);

        $this->reset('postData');
        $this->postData = new PostData();
        $this->modalTitle = 'Create New Post';
        $this->showModal = true;
    }

    public function edit(Post $post)
    {
        Gate::authorize('update', $post);
        // $this->authorize('update', $post);

        $this->postData = PostData::fromModel($post);
        $this->modalTitle = 'Edit Post';
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if (isset($this->postData->id)) {
            $post = Post::findOrFail($this->postData->id);
            Gate::authorize('update', $post);
            // $this->authorize('update', $post);
            $post->update($this->postData->toArray());
            session()->flash('message', 'Post updated successfully!');
        } else {
            Gate::authorize('create', Post::class);
            // $this->authorize('create', Post::class);
            $post = auth()->user()->posts()->create($this->postData->toArray());
            session()->flash('message', 'Post created successfully!');
            $this->dispatch('post-created');
        }

        $this->showModal = false;
    }

    public function confirmDelete(string $postId)
    {
        $this->confirmationPostId = $postId;
    }

    public function delete()
    {
        $post = Post::findOrFail($this->confirmationPostId);
        Gate::authorize('delete', $post);
        // $this->authorize('delete', $post);

        $post->delete();
        $this->confirmationPostId = '';
        session()->flash('message', 'Post deleted successfully!');
    }

    public function cancel()
    {
        $this->showModal = false;
        $this->confirmationPostId = '';
    }

    public function render()
    {
        $posts = Post::with('user')
            ->when(!auth()->user()->isAdmin(), fn($q) => $q->where('user_id', auth()->id()))
            ->latest()
            ->paginate(10);

        return view('livewire.posts.posts', [
            'posts' => $posts,
        ]);
    }


    /**
     *
     * Log de ciclos de vida - Lavawire
     *
     */

    /**
     * Chamado uma vez quando o componente é inicializado.
     */
    public function boot()
    {
        Log::info('boot: Componente Livewire inicializado.');
    }

    /**
     * Chamado após o método boot.
     */
    public function booted()
    {
        Log::info('booted: Componente Livewire totalmente carregado.');
    }

    /**
     * Antes da renderização
     * Chamado automaticamente antes de cada atualização do componente.
     * Útil para preparar dados antes de renderizar.
     */
    public function hydrate()
    {
        Log::info('hydrate: Componente hidratado.');
    }

    /**
     * Após renderização, antes do envio
     * Chamado automaticamente após cada atualização do componente.
     * Útil para limpar ou ajustar dados antes de enviar para o cliente.
     */
    public function dehydrate()
    {
        Log::info('dehydrate: Componente dessidratado.');
        $this->lastUpdated = now();
    }

    /**
     * Chamado antes de uma propriedade pública ser atualizada.
     */
    public function updating($propertyName, $value)
    {
        Log::info('updating: propriedade pública ser atualizada.');

    }

    /**
     * Chamado automaticamente quando uma propriedade pública é atualizada.
     */
    public function updated($modalTitle)
    {
        Log::info("A propriedade {$modalTitle} foi atualizada.");
    }

    /**
     * Chamado automaticamente após uma propriedade específica ser desidratada.
     */
    public function dehydrateModalTitle()
    {
        Log::info('dehydrateModalTitle: Var modalTitle desidratada.');
        // $this->modalTitle = strtoupper($this->modalTitle);
    }

    /**
     * Chamado quando o componente é destruído.
     */
    public function destroy()
    {
        Log::info('destroy: Componente Livewire destruído.');
    }

}
