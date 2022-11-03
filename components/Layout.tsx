import Navbar from "../islands/Navbar.tsx";
import Footer from "./Footer.tsx";

export default function Layout({ children }) {
  return (
    <div>
      <div
        id="scroll-tracker"
        class="fixed inset-0 h-2 origin-left bg-gradient-to-r  from-blue-900 via-blue-500 to-blue-900 shadow-sm shadow-blue-500 z-10"
      />
      <Navbar />
      {children}
      <Footer />
    </div>
  );
}
