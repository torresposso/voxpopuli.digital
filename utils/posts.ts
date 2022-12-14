export interface Post {
  id: string;
  title: string;
  date: string;
  slug: string;
  excerpt: string;
  content: string;
  categories: {
    nodes: [{ name: string }];
  };
  author: {
    node: {
      name: string;
      uri: string;
      avatar: {
        url: string;
      };
    };
  };

  featuredImage: {
    node: {
      sourceUrl: string;
      srcSet: string;
    };
  };
}

async function fetchAPI(query: string, { variables }: any = {}) {
  const headers = { "Content-Type": "application/json" };
  const res = await fetch("https://voxpopuli.digital/graphql", {
    method: "POST",
    headers,
    body: JSON.stringify({ query, variables }),
  });

  const json = await res.json();
  if (json.errors) {
    console.log(json.errors);
    throw new Error("Failed to fetch API");
  }

  return json.data;
}

export async function getAllPagesWithSlugs() {
  const data = await fetchAPI(`
      {
        pages(first: 10000) {
          edges {
            node {
              slug
            }
          }
        }
      }
      `);
  return data?.pages;
}

export async function getPageBySlug(slug: string) {
  const data = await fetchAPI(`
      {
        page(id: "${slug}", idType: URI) {
          title
          content
        }
      }
      `);
  return data?.page;
}

export const getLatestPosts = async () => {
  const data = await fetchAPI(`
      {
        posts(first:5) {
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
  `);
  return data?.posts?.nodes;
};

export const getStickyPosts = async () => {
  const data = await fetchAPI(`
      {
        posts( where: {onlySticky: true } first:5) {
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
  `);

  return data?.posts?.nodes;
};

export async function getPrimaryMenu() {
  const data = await fetchAPI(`
    {
      menu(id:"principal", idType:SLUG) {
        menuItems {
          nodes {
            label
            uri
          }
        }
      }
    }
    `);
  return data?.menu?.menuItems?.nodes;
}
