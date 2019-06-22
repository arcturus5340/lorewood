def f(k: int):
    if k in res_set:
        return
    res_set.add(k)
    new_k = k + 1
    while new_k % 10 == 0:
        new_k //= 10
    f(new_k)

res_set = set()
n = int(input())
f(n)

print(len(res_set))