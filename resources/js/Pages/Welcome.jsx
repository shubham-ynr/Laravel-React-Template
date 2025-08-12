import ThemeSwitcher from "@/components/theme-switcher";

export default function Welcome() {
  console.log("Ziggy Route demo");
  console.log(route('Welcome'));
  return (
    <div className="h-dvh w-dvw flex items-center justify-center text-center">
      <h1 className="xl:text-4xl text-xl font-bold">Welcome to the Laravel React Template</h1>
      <ThemeSwitcher className="absolute top-4 right-4" />
    </div>
  )
}