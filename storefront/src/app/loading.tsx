export default function Loading() {
  return (
    <main className="min-h-screen bg-[#F6F3EC]">
      <section className="mx-auto max-w-7xl px-6 py-8">
        <div className="mb-8 flex items-center justify-between gap-6 rounded-[1.5rem] border border-black/[0.06] bg-white/80 px-6 py-4 shadow-sm shadow-black/[0.03]">
          <div className="h-12 w-44 animate-pulse rounded-full bg-[#10213F]/10" />
          <div className="hidden gap-3 md:flex">
            <div className="h-10 w-24 animate-pulse rounded-full bg-[#10213F]/10" />
            <div className="h-10 w-24 animate-pulse rounded-full bg-[#10213F]/10" />
            <div className="h-10 w-28 animate-pulse rounded-full bg-[#10213F]/10" />
          </div>
        </div>

        <div className="grid overflow-hidden rounded-[2.25rem] bg-gradient-to-br from-[#10213F] via-[#0D47C9] to-[#0B6FEF] p-8 shadow-2xl shadow-blue-950/16 md:p-10 lg:grid-cols-[1.05fr_0.95fr] lg:items-center lg:p-12">
          <div>
            <div className="h-10 w-56 animate-pulse rounded-full bg-white/15" />
            <div className="mt-8 h-16 w-full max-w-2xl animate-pulse rounded-3xl bg-white/15" />
            <div className="mt-4 h-16 w-4/5 animate-pulse rounded-3xl bg-white/15" />
            <div className="mt-8 h-24 w-full max-w-2xl animate-pulse rounded-3xl bg-white/10" />
            <div className="mt-9 flex gap-3">
              <div className="h-12 w-40 animate-pulse rounded-full bg-[#FFB000]/70" />
              <div className="h-12 w-36 animate-pulse rounded-full bg-white/15" />
            </div>
          </div>

          <div className="mt-10 lg:mt-0">
            <div className="ml-auto max-w-[430px] rounded-[2rem] border border-white/15 bg-white/10 p-5 backdrop-blur">
              <div className="rounded-[1.65rem] bg-white p-6 shadow-xl shadow-black/10">
                <div className="h-8 w-48 animate-pulse rounded-full bg-[#10213F]/10" />
                <div className="mt-6 grid gap-3">
                  <div className="h-20 animate-pulse rounded-2xl bg-[#10213F]/8" />
                  <div className="h-20 animate-pulse rounded-2xl bg-[#10213F]/8" />
                  <div className="h-20 animate-pulse rounded-2xl bg-[#10213F]/8" />
                </div>
              </div>
            </div>
          </div>
        </div>

        <section className="py-14">
          <div className="mb-8 h-10 w-72 animate-pulse rounded-full bg-[#10213F]/10" />
          <div className="grid gap-5 md:grid-cols-4">
            {Array.from({ length: 8 }).map((_, index) => (
              <div
                key={index}
                className="h-36 animate-pulse rounded-[1.5rem] bg-white/80 shadow-sm shadow-black/[0.03]"
              />
            ))}
          </div>
        </section>

        <section className="py-8">
          <div className="grid gap-5 md:grid-cols-3">
            {Array.from({ length: 6 }).map((_, index) => (
              <div
                key={index}
                className="h-96 animate-pulse rounded-[1.75rem] bg-white/80 shadow-sm shadow-black/[0.03]"
              />
            ))}
          </div>
        </section>
      </section>
    </main>
  );
}
