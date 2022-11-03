import { Head } from "$fresh/runtime.ts";
import { AppProps } from "$fresh/src/server/types.ts";

export default function App({ Component }: AppProps) {
  return (
    <>
      <Head>
        <link rel="icon" href="/logo.ico" />
        <link rel="stylesheet" href="/app.css" />
        <script src="/app.js" type="module"></script>
      </Head>
      <Component />
    </>
  );
}
