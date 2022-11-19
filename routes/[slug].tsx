import { Handlers, PageProps } from "$fresh/server.ts";
import Layout from "../components/Layout.tsx";
import PostHeader from "../components/PostHeader.tsx";
import { Post } from "../utils/posts.ts";

export const handler: Handlers = {
  async GET(_, ctx) {
    const query = `
    {
      post(idType:SLUG id:"${ctx.params.slug}") {
        title
        categories {
          nodes {
            name
          }
        }
        content
        date
        author {
          node {
            name
            uri
            posts(first:5) {
              nodes {
                title
                uri
              }
            }
            avatar {
              url
              
            }
          }
        }
      }
    }
    `;
    const headers = { "Content-Type": "application/json" };
    const resp = await fetch("https://voxpopuli.digital/graphql", {
      method: "POST",
      headers,
      body: JSON.stringify({ query }),
    });

    if (resp.status === 404) {
      return ctx.render(null);
    }

    const { data: { post } } = await resp.json();

    return ctx.render(post);
  },
};

export default function Greet(props: PageProps<Post>) {
  const post = props.data;
  return (
    <Layout>
      <div class="container mx-auto">
        <article>
          <PostHeader
            title={post?.title}
            categories={post?.categories?.nodes}
          />

          <main dangerouslySetInnerHTML={{ __html: post?.content }}></main>
        </article>
      </div>
    </Layout>
  );
}
