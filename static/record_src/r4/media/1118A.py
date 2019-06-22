q = int(input())
for _ in range(q):
    n, a, b = [int(_) for _ in input().split()]
    print(min(a*n, a*(n % 2) + b*(n // 2)))
