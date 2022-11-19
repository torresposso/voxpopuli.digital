interface PostHeader {
  title?: string;
  date?: string;
  categories?: [{ name: string }];
  author?: {
    node: {
      name: string;
      uri: string;
      avatar: {
        url: string;
      };
    };
  };
}

const PostHeader = (props: PostHeader) => {
  return (
    <header>
      <div class="flex space-x-3 text-white text-[12px]">
        {props.categories?.map((category) => (
          <span class="bg-blue-900 px-2 py-1 rounded-lg">
            {category.name}
          </span>
        ))}
      </div>
      <h1 class="font-semibold text-4xl">{props.title}</h1>
    </header>
  );
};

export default PostHeader;
