import { FunctionalComponent } from "preact";
import { Post } from "../routes/index.tsx";

export interface Props {
  destacadas: Post[];
}

const formatDate = (date: string) => {
  const dateConstructor = new Date(date);
  return new Intl.DateTimeFormat("es-CO", {
    dateStyle: "full",
  }).format(dateConstructor);
};

const Destacadas: FunctionalComponent<Props> = (
  { destacadas },
) => {
  return (
    <>
      <div class="container mx-auto">
        <div class="flex flex-col lg:flex-row lg:space-x-2 ">
          <div class="lg:w-3/4 border-t-2">
            <div class="pb-4">
              <span class="bg-white  py-2 px-4 text-white font-mono font-semibold tracking-wider inline">
                DESTACADAS!!!
              </span>
            </div>
            <div class="flex flex-col lg:flex-row lg:space-x-8">
              <div class="lg:w-2/6  flex flex-col justify-between">
                {destacadas.slice(2).map((post) => (
                  <>
                    {/*<!-- Component: Basic image card --> */}
                    <div className="overflow-hidden bg-white text-slate-500">
                      {/*  <!--  Image --> */}
                      <div
                        class="aspect-w-16 aspect-h-9 bg-cover"
                        style={{
                          backgroundImage:
                            `url(${post.featuredImage.node.sourceUrl})`,
                        }}
                      >
                        {
                          /* <img
                      src={post.featuredImage.node.sourceUrl}
                      alt="card image"
                      class="bg-cover"
                    /> */
                        }
                      </div>

                      {/*  <!-- Body--> */}
                      <div className="py-4">
                        <header className="">
                          <p className="text-sm text-slate-400">
                            {post.categories.nodes[0].name}
                          </p>
                          <h3 className="py-1 font-mono text-xl font-semibold leading-6 text-slate-900">
                            {post.title}
                          </h3>
                          <p className="text-sm text-slate-400">
                            {formatDate(post.date)}
                          </p>
                        </header>
                      </div>
                    </div>
                    {/*<!-- End Basic image card --> */}
                  </>
                ))}
              </div>
              <div class="lg:w-4/6 -order-1 lg:order-1">
                <div class="">
                  <div
                    class="aspect-w-16 aspect-h-9  bg-cover"
                    style={{
                      backgroundImage: `url(${
                        destacadas[2].featuredImage.node.sourceUrl
                      })`,
                    }}
                  >
                    {
                      /* <img
                      src={destacadas[0].featuredImage.node.sourceUrl}
                      alt=""
                      class="bg-cover" />*/
                    }
                  </div>
                  <div class="py-6 w-full space-y-1">
                    <span>{destacadas[3].categories.nodes[0].name}</span>
                    <h3 class="text-3xl font-semibold tracking-wide ">
                      {destacadas[3].title}
                    </h3>

                    <div
                      class="py-2"
                      dangerouslySetInnerHTML={{
                        __html: destacadas[3].excerpt,
                      }}
                    >
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="lg:w-1/4 border-t-2 border-black">
            <span class="bg-blue-900 py-2 px-4 text-white font-mono font-semibold tracking-wider inline">
              LO ÚLTIMO
            </span>
            <div>rigth</div>
          </div>
        </div>
      </div>
    </>
  );
};

export default Destacadas;
