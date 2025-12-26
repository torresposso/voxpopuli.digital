const API_URL = "https://voxpopuli.digital/wp-json/wp/v2";

/* ======================================================
   RAW WORDPRESS API TYPES
   ====================================================== */

export interface WPApiPostsResponse extends Array<WPPost> { }

export interface WPPost {
  id: number;
  date: string;
  date_gmt: string;
  slug: string;
  status: string;
  type: string;
  link: string;

  title: {
    rendered: string;
  };

  content: {
    rendered: string;
    protected: boolean;
  };

  excerpt: {
    rendered: string;
    protected: boolean;
  };

  author: number;
  featured_media: number;
  categories: number[];
  tags: number[];

  _embedded?: {
    author?: Array<{
      id: number;
      name: string;
      avatar_urls: {
        "24": string;
        "48": string;
        "96": string;
      };
    }>;

    "wp:featuredmedia"?: Array<{
      id: number;
      source_url: string;
      alt_text: string;
      media_details?: {
        width: number;
        height: number;
      };
    }>;

    "wp:term"?: Array<
      Array<{
        id: number;
        name: string;
        slug: string;
        taxonomy: string;
      }>
    >;
  };
}

/* ======================================================
   VIEW MODEL (WHAT YOUR APP ACTUALLY USES)
   ====================================================== */

export interface PostViewModel {
  id: number;
  slug: string;
  url: string;

  title: string;
  excerptHtml: string;
  contentHtml: string;

  publishedAt: string;

  author: {
    id: number;
    name: string;
    avatarUrl?: string;
  };

  featuredImage?: {
    url: string;
    alt: string;
    width?: number;
    height?: number;
  };

  categories: Array<{
    id: number;
    name: string;
    slug: string;
  }>;
}

/* ======================================================
   MAPPER: WORDPRESS → VIEW MODEL
   ====================================================== */

function mapWPPostToViewModel(post: WPPost): PostViewModel {
  const author = post._embedded?.author?.[0];
  const media = post._embedded?.["wp:featuredmedia"]?.[0];

  const categories =
    post._embedded?.["wp:term"]
      ?.flat()
      .filter(term => term.taxonomy === "category")
      .map(term => ({
        id: term.id,
        name: term.name,
        slug: term.slug,
      })) ?? [];

  return {
    id: post.id,
    slug: post.slug,
    url: post.link,

    title: post.title.rendered,
    excerptHtml: post.excerpt.rendered,
    contentHtml: post.content.rendered,

    publishedAt: post.date,

    author: {
      id: author?.id ?? post.author,
      name: author?.name ?? "Unknown",
      avatarUrl: author?.avatar_urls?.["96"],
    },

    featuredImage: media
      ? {
        url: media.source_url,
        alt: media.alt_text,
        width: media.media_details?.width,
        height: media.media_details?.height,
      }
      : undefined,

    categories,
  };
}

/* ======================================================
   FETCH FUNCTION (PUBLIC API)
   ====================================================== */


export async function getLatestPostsFromCategoryId(categoryId: number, postCount = 5): Promise<PostViewModel[]> {
  const res = await fetch(
    `${API_URL}/posts?categories=${categoryId}&per_page=${postCount}&_embed`
  );

  if (!res.ok) {
    throw new Error("Failed to fetch WordPress posts");
  }

  const posts = (await res.json()) as WPApiPostsResponse;

  return posts.map(mapWPPostToViewModel);
}

// Get all posts (limited to 10 for now, WP default is 10)
export async function getAllPosts(): Promise<PostViewModel[]> {
  const res = await fetch(`${API_URL}/posts?per_page=10&_embed`);

  if (!res.ok) {
    throw new Error("Failed to fetch WordPress posts");
  }

  const posts = (await res.json()) as WPApiPostsResponse;

  return posts.map(mapWPPostToViewModel);
}

// Get single post by slug
export async function getPostBySlug(slug: string): Promise<PostViewModel | null> {
  const res = await fetch(`${API_URL}/posts?slug=${slug}&_embed`);

  if (!res.ok) {
    throw new Error("Failed to fetch WordPress post");
  }

  const posts = (await res.json()) as WPApiPostsResponse;

  return posts.length > 0 ? mapWPPostToViewModel(posts[0]) : null;
}