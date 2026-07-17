@if (! post_password_required())
  <section id="comments" class="comments space-y-6 border-t-2 border-base-300 pt-8">
    @if ($responses())
      <h2 class="font-display font-bold text-[1.75rem] tracking-tight text-base-content">
        {!! $title !!}
      </h2>

      <ol class="comment-list space-y-4">
        {!! $responses !!}
      </ol>

      @if ($paginated())
        <nav aria-label="Comment" class="flex gap-2">
          @if ($previous())
            {!! $previous !!}
          @endif

          @if ($next())
            {!! $next !!}
          @endif
        </nav>
      @endif
    @endif

    @php(comment_form([
      'title_reply' => __('Deja un comentario', 'voxpopuli'),
      'label_submit' => __('Publicar comentario', 'voxpopuli'),
      'comment_field' => '<textarea id="comment" name="comment" class="textarea w-full" rows="4" placeholder="'.esc_attr__('Escribe tu comentario…', 'voxpopuli').'" required></textarea>',
      'fields' => [
        'author' => '<input id="author" name="author" class="input w-full" type="text" placeholder="'.esc_attr__('Nombre', 'voxpopuli').'" required />',
        'email' => '<input id="email" name="email" class="input w-full" type="email" placeholder="'.esc_attr__('Correo electrónico', 'voxpopuli').'" required />',
        'url' => '<input id="url" name="url" class="input w-full" type="url" placeholder="'.esc_attr__('Sitio web (opcional)', 'voxpopuli').'" />',
      ],
      'class_submit' => 'btn btn-primary',
      'class_form' => 'space-y-4',
    ]))
  </section>
@endif
