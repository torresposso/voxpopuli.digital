import { Head } from "$fresh/runtime.ts";
import Layout from "../components/Layout.tsx";

import { Handlers, PageProps } from "$fresh/server.ts";
import { Post } from "../utils/posts.ts";

export const handler: Handlers = {
  async GET(_, ctx) {
    const query = `
    {
      stickyPosts: posts(where: {onlySticky: true}, first: 4) {
        nodes {
          id
          title
          date
          slug
          excerpt
          categories {
            nodes {
              name
            }
          }
          featuredImage {
            node {
              sourceUrl
              srcSet
            }
          }
        }
      }
      latestPosts: posts(first: 6) {
        nodes {
          id
          title
          date
          slug
          excerpt
          categories {
            nodes {
              name
            }
          }
          featuredImage {
            node {
              sourceUrl
              srcSet
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

    const { data: { stickyPosts, latestPosts } } = await resp.json();

    return ctx.render({
      stickyPosts: stickyPosts.nodes,
      latestPosts: latestPosts.nodes,
    });
  },
};

const formatDate = (date: string) => {
  const dateConstructor = new Date(date);
  return new Intl.DateTimeFormat("es-CO", {
    dateStyle: "full",
  }).format(dateConstructor);
};

export default function Home({ data }: PageProps) {
  return (
    <>
      <Head>
        <title>Voxpopuli Digital</title>
      </Head>
      <Layout>
        <div class="container mx-auto">
          <div class="flex flex-col lg:flex-row divide-x ">
            <div class="lg:w-3/4">
              <div class="border-t-2 border-black mr-5">
                <div class="pb-4">
                  <span class="bg-blue-900  py-2 px-4 text-white font-mono font-semibold tracking-wider inline">
                    DESTACADAS
                  </span>
                </div>
                <div class="flex flex-col lg:flex-row lg:space-x-8 ">
                  <div class="lg:w-2/6  flex flex-col justify-between divide-y">
                    {data.stickyPosts.slice(2).map((post: Post) => (
                      <div className="overflow-hidden text-slate-500">
                        <div
                          class="aspect-w-16 aspect-h-9 bg-cover"
                          style={{
                            backgroundImage:
                              `url(${post.featuredImage.node.sourceUrl})`,
                          }}
                        >
                        </div>

                        <div className="py-4">
                          <header className="">
                            <span className="px-2 py-1 text-[11px] text-white bg-blue-900 rounded-lg">
                              {post.categories.nodes[0].name}
                            </span>
                            <h3 className="py-1 font-mono text-xl font-semibold leading-6 text-slate-900">
                              {post.title}
                            </h3>
                            <p className="text-sm text-slate-400">
                              {formatDate(post.date)}
                            </p>
                          </header>
                        </div>
                      </div>
                    ))}
                  </div>
                  <div class="lg:w-4/6 -order-1 lg:order-1">
                    <div class="">
                      <div
                        class="aspect-w-16 aspect-h-9  bg-cover"
                        style={{
                          backgroundImage: `url(${
                            data.stickyPosts[2].featuredImage.node.sourceUrl
                          })`,
                        }}
                      >
                      </div>
                      <div class="py-6 w-full space-y-1">
                        <span className="px-2 py-1 text-[11px] text-white bg-blue-900 rounded-lg">
                          {data.stickyPosts[2].categories.nodes[0].name}
                        </span>
                        <h3 class="text-3xl font-semibold tracking-wide ">
                          {data.stickyPosts[2].title}
                        </h3>

                        <div
                          class="py-2"
                          dangerouslySetInnerHTML={{
                            __html: data.stickyPosts[2].excerpt,
                          }}
                        >
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="lg:w-1/4">
              <div class="ml-5 border-t-2 border-black ">
                <span class="bg-blue-900  py-2 px-4 text-white font-mono font-semibold tracking-wider inline">
                  LO ÚLTIMO
                </span>
                <div class="flex flex-col justify-between divide-y">
                  {data.latestPosts.map((post: Post) => (
                    <div class="flex items-center my-2 py-2">
                      <div class="w-3/4 pr-2">
                        <h3 class="font-mono font-semibold">
                          <a href={`/${post.slug}`}>{post.title}</a>
                        </h3>
                        <time className="text-sm text-slate-400">
                          {formatDate(post.date)}
                        </time>
                      </div>
                      <div class="w-1/4">
                        <div
                          class="aspect-w-1 aspect-h-1 bg-cover"
                          style={{
                            backgroundImage:
                              `url(${post.featuredImage?.node?.sourceUrl})`,
                          }}
                        />
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          </div>
        </div>
      </Layout>
    </>
  );
}
